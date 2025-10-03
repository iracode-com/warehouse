<?php

namespace App\Services;

use App\Models\Rule;
use App\Models\Alert;
use App\Models\Category;
use App\Models\CategoryAttributeValue;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Notification;
use Illuminate\Support\Collection;

class RuleEngine
{
    /**
     * Process all active rules that need checking
     */
    public function processRules(): int
    {
        $rules = Rule::active()
            ->needsCheck()
            ->orderedByPriority()
            ->get();

        $processedCount = 0;

        foreach ($rules as $rule) {
            try {
                if ($this->processRule($rule)) {
                    $processedCount++;
                }
            } catch (\Exception $e) {
                Log::error("Rule processing failed for rule {$rule->id}: " . $e->getMessage());
            }
        }

        return $processedCount;
    }

    /**
     * Process a specific rule
     */
    public function processRule(Rule $rule): bool
    {
        if (!$rule->is_active) {
            return false;
        }

        $rule->updateLastChecked();

        // Get categories that match this rule
        $categories = $this->getCategoriesForRule($rule);

        foreach ($categories as $category) {
            if ($this->evaluateRule($rule, $category)) {
                $this->createAlert($rule, $category);
                $rule->incrementTriggerCount();
            }
        }

        return true;
    }

    /**
     * Get categories that match the rule criteria
     */
    protected function getCategoriesForRule(Rule $rule): Collection
    {
        $query = Category::query();

        if ($rule->category_id) {
            // Rule applies to specific category and its descendants
            $category = Category::find($rule->category_id);
            if ($category) {
                $query->where(function ($q) use ($category) {
                    $q->where('id', $category->id)
                      ->orWhere('full_path', 'like', $category->full_path . '%');
                });
            }
        }

        return $query->get();
    }

    /**
     * Evaluate if a rule condition is met for a category
     */
    protected function evaluateRule(Rule $rule, Category $category): bool
    {
        if (!$rule->attribute_id) {
            return $this->evaluateCustomRule($rule, $category);
        }

        $attributeValue = CategoryAttributeValue::where('category_id', $category->id)
            ->where('attribute_id', $rule->attribute_id)
            ->first();

        if (!$attributeValue) {
            return $this->evaluateNullCondition($rule);
        }

        return $this->evaluateCondition($rule, $attributeValue->getValue());
    }

    /**
     * Evaluate condition based on rule type and condition type
     */
    protected function evaluateCondition(Rule $rule, $value): bool
    {
        $conditionValue = $rule->getConditionValue();
        $conditionValues = $rule->getConditionValuesArray();

        return match ($rule->condition_type) {
            'equals' => $value == $conditionValue,
            'not_equals' => $value != $conditionValue,
            'greater_than' => $value > $conditionValue,
            'less_than' => $value < $conditionValue,
            'greater_equal' => $value >= $conditionValue,
            'less_equal' => $value <= $conditionValue,
            'contains' => str_contains((string) $value, (string) $conditionValue),
            'not_contains' => !str_contains((string) $value, (string) $conditionValue),
            'in' => in_array($value, $conditionValues),
            'not_in' => !in_array($value, $conditionValues),
            'between' => $value >= $conditionValues[0] && $value <= $conditionValues[1],
            'not_between' => $value < $conditionValues[0] || $value > $conditionValues[1],
            'is_null' => $value === null,
            'is_not_null' => $value !== null,
            default => false,
        };
    }

    /**
     * Evaluate null condition (when attribute value doesn't exist)
     */
    protected function evaluateNullCondition(Rule $rule): bool
    {
        return match ($rule->condition_type) {
            'is_null' => true,
            'is_not_null' => false,
            default => false,
        };
    }

    /**
     * Evaluate custom rule (when no specific attribute is targeted)
     */
    protected function evaluateCustomRule(Rule $rule, Category $category): bool
    {
        // Implement custom rule logic based on rule metadata
        $metadata = $rule->getMetadataArray();
        
        // Example: Check if category has no children (leaf category)
        if (isset($metadata['check_leaf']) && $metadata['check_leaf']) {
            return $category->is_leaf;
        }

        // Example: Check if category has too many children
        if (isset($metadata['max_children'])) {
            return $category->children_count > $metadata['max_children'];
        }

        // Example: Check if category is inactive
        if (isset($metadata['check_inactive']) && $metadata['check_inactive']) {
            return $category->status === 'inactive';
        }

        return false;
    }

    /**
     * Create alert for rule violation
     */
    protected function createAlert(Rule $rule, Category $category): Alert
    {
        $alert = Alert::create([
            'rule_id' => $rule->id,
            'category_id' => $category->id,
            'attribute_id' => $rule->attribute_id,
            'title' => $rule->alert_title,
            'message' => $this->formatAlertMessage($rule, $category),
            'alert_type' => $rule->alert_type,
            'status' => 'pending',
            'trigger_data' => $this->getTriggerData($rule, $category),
            'recipients' => $rule->getAlertRecipientsArray(),
            'priority' => $rule->priority,
            'is_read' => false,
        ]);

        // Send notification if configured
        $this->sendNotification($alert);

        return $alert;
    }

    /**
     * Format alert message with dynamic values
     */
    protected function formatAlertMessage(Rule $rule, Category $category): string
    {
        $message = $rule->alert_message;
        
        // Replace placeholders with actual values
        $message = str_replace('{category_name}', $category->name, $message);
        $message = str_replace('{category_code}', $category->code, $message);
        $message = str_replace('{rule_name}', $rule->name, $message);
        $message = str_replace('{condition_value}', $rule->condition_value, $message);
        
        return $message;
    }

    /**
     * Get trigger data for alert
     */
    protected function getTriggerData(Rule $rule, Category $category): array
    {
        $data = [
            'rule_id' => $rule->id,
            'rule_name' => $rule->name,
            'category_id' => $category->id,
            'category_name' => $category->name,
            'category_code' => $category->code,
            'condition_type' => $rule->condition_type,
            'condition_value' => $rule->condition_value,
            'triggered_at' => now()->toISOString(),
        ];

        if ($rule->attribute_id) {
            $attributeValue = CategoryAttributeValue::where('category_id', $category->id)
                ->where('attribute_id', $rule->attribute_id)
                ->first();
            
            if ($attributeValue) {
                $data['current_value'] = $attributeValue->getValue();
                $data['attribute_name'] = $rule->attribute->label;
            }
        }

        return $data;
    }

    /**
     * Send notification for alert
     */
    protected function sendNotification(Alert $alert): void
    {
        try {
            // Mark as sent
            $alert->markAsSent();

            // Log the alert
            Log::info("Alert created: {$alert->title}", [
                'alert_id' => $alert->id,
                'rule_id' => $alert->rule_id,
                'category_id' => $alert->category_id,
                'priority' => $alert->priority,
            ]);

            // TODO: Implement actual notification sending (email, SMS, etc.)
            // This could be done through Laravel notifications, queues, etc.
            
        } catch (\Exception $e) {
            Log::error("Failed to send notification for alert {$alert->id}: " . $e->getMessage());
        }
    }

    /**
     * Process rules for a specific category
     */
    public function processRulesForCategory(Category $category): int
    {
        $rules = Rule::active()
            ->where(function ($query) use ($category) {
                $query->where('category_id', $category->id)
                      ->orWhereNull('category_id');
            })
            ->orderedByPriority()
            ->get();

        $processedCount = 0;

        foreach ($rules as $rule) {
            try {
                if ($this->evaluateRule($rule, $category)) {
                    $this->createAlert($rule, $category);
                    $rule->incrementTriggerCount();
                    $processedCount++;
                }
            } catch (\Exception $e) {
                Log::error("Rule processing failed for rule {$rule->id} and category {$category->id}: " . $e->getMessage());
            }
        }

        return $processedCount;
    }

    /**
     * Get statistics about rules and alerts
     */
    public function getStatistics(): array
    {
        return [
            'total_rules' => Rule::count(),
            'active_rules' => Rule::active()->count(),
            'realtime_rules' => Rule::active()->realtime()->count(),
            'total_alerts' => Alert::count(),
            'active_alerts' => Alert::active()->count(),
            'unread_alerts' => Alert::unread()->count(),
            'critical_alerts' => Alert::critical()->count(),
            'alerts_by_type' => Alert::selectRaw('alert_type, count(*) as count')
                ->groupBy('alert_type')
                ->pluck('count', 'alert_type')
                ->toArray(),
            'alerts_by_status' => Alert::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->pluck('count', 'status')
                ->toArray(),
        ];
    }

    /**
     * Clean up old alerts
     */
    public function cleanupAlerts(int $daysOld = 30): int
    {
        $cutoffDate = now()->subDays($daysOld);
        
        return Alert::where('created_at', '<', $cutoffDate)
            ->whereIn('status', ['resolved', 'dismissed'])
            ->delete();
    }
}
