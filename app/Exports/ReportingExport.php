<?php

namespace App\Exports;

use Illuminate\Support\Arr;
use App\Support\Utils;
use App\Models\Reporting\Report;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithProperties;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Excel;
use ReflectionClass;
use Illuminate\Support\Facades\Log;

class ReportingExport implements FromArray, WithHeadings, WithProperties, WithEvents
{
    use Exportable;

    public Report $report;
    private string $fileName;
    private string $writerType;
    private array $headers;
    private bool $hasLogo;

    public function __construct(Report $report)
    {
        $this->report = $report;
        $this->writerType = match ($report->export_type) {
            'xlsx' => Excel::XLSX,
            'xls' => Excel::XLS,
            'csv' => Excel::CSV,
            'pdf' => Excel::MPDF,
            'chart' => Excel::MPDF,
            default => Excel::XLSX,
        };

        $fileExtension = match ($report->export_type) {
            'xlsx' => 'xlsx',
            'xls' => 'xls',
            'csv' => 'csv',
            'pdf' => 'pdf',
            'chart' => 'pdf',
            default => 'xlsx',
        };

        $filename = $report->title . '_' . verta()->timestamp . '.' . $fileExtension;
        $this->fileName = $filename;
        $this->hasLogo = !empty($report->logo);

        // Configure DomPDF for Persian/Arabic
        if (in_array($report->export_type, ['pdf', 'chart'])) {
            $this->configureDomPDF();
        }
    }

    private function configureDomPDF()
    {
        config([
            'excel.exports.pdf.driver' => 'dompdf',
            'excel.exports.pdf.options' => [
                'defaultFont' => 'iransans',
                'isHtml5ParserEnabled' => true,
                'isRemoteEnabled' => true,
                'isPhpEnabled' => true,
                'chroot' => realpath(base_path()),
                'fontDir' => storage_path('fonts/'),
                'fontCache' => storage_path('fonts/'),
                'tempDir' => storage_path('app/temp/'),
            ]
        ]);
    }

    private function configureMPDF()
    {
        config([
            'excel.exports.pdf.driver' => 'mpdf',
            'excel.exports.pdf.options' => [
                'defaultFont' => 'iransans',
                'mode' => 'utf-8',
                'format' => 'A4',
                'margin_header' => 3,
                'margin_top' => 10,
                'margin_bottom' => 10,
                'margin_footer' => 2,
                'orientation' => 'L',
                'directionality' => 'rtl',
                'autoScriptToLang' => true,
                'autoLangToFont' => true,
            ]
        ]);
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                // For Excel files
                if (in_array($this->report->export_type, ['xlsx', 'xls'])) {
                    $event->sheet->setRightToLeft(true);
                    $event->sheet->getStyle('A:Z')
                        ->getFont()
                        ->setName('B Nazanin')
                        ->setSize(10);
                }

                // For PDF
                if (in_array($this->report->export_type, ['pdf', 'chart'])) {
                    $this->applyPdfStyling($event);
                }
            },

            // For CSV - add BOM for UTF-8
            \Maatwebsite\Excel\Events\BeforeWriting::class => function (\Maatwebsite\Excel\Events\BeforeWriting $event) {
                if ($this->report->export_type === 'csv') {
                    $event->writer->setUseBOM(true);
                }
            },
        ];
    }

    public function array(): array
    {
        // Get query configuration from report
        $queryConfig = $this->report->query ?? [];
        
        Log::info('ReportingExport array() called', [
            'report_id' => $this->report->id,
            'queryConfig_empty' => empty($queryConfig),
            'queryConfig' => $queryConfig,
            'has_record_ids' => isset($queryConfig['record_ids']),
            'record_ids_count' => isset($queryConfig['record_ids']) ? count($queryConfig['record_ids']) : 0
        ]);
        
        if (empty($queryConfig)) {
            Log::warning('Query config is empty, using fallback data');
            // Fallback to old method if query config doesn't exist
            return $this->report->data ?? [[]];
        }

        // Get the model class
        $modelClass = $this->report->reportable_type;
        
        // Build query from saved record IDs
        $query = $modelClass::query();
        
        // Use saved record IDs (most reliable method)
        if (!empty($queryConfig['record_ids'])) {
            $query->whereIn('id', $queryConfig['record_ids']);
            
            Log::info('Using saved record IDs for export', [
                'model' => $modelClass,
                'count' => count($queryConfig['record_ids']),
                'first_5_ids' => array_slice($queryConfig['record_ids'], 0, 5)
            ]);
        } else {
            Log::warning('No record IDs found in query config', [
                'queryConfig_keys' => array_keys($queryConfig)
            ]);
        }
        
        // Load relationships
        $relationships = $queryConfig['relationships'] ?? [];
        if (!empty($relationships)) {
            $query->with($relationships);
        }
        
        // Get all records
        $records = $query->get();
        
        Log::info('Records retrieved for export', [
            'retrieved_count' => $records->count()
        ]);
        
        // Get column types for tinyint handling
        $columnTypes = $this->getModelColumnTypes($modelClass);
        
        // Process records to array format
        $exportData = [];
        $columns = $queryConfig['columns'] ?? $this->report->header ?? [];
        
        foreach ($records as $record) {
            $row = [];
            
            foreach ($columns as $column) {
                if (str_contains($column, '.')) {
                    // Handle relationship columns
                    $parts = explode('.', $column);
                    $relationName = $parts[0];
                    $attribute = $parts[1];
                    
                    try {
                        $relatedModel = $record->getRelation($relationName);
                        
                        if ($relatedModel) {
                            if (is_iterable($relatedModel) && !is_array($relatedModel)) {
                                $relatedModel = $relatedModel->first();
                            }
                            $row[$column] = $relatedModel ? $relatedModel->getAttribute($attribute) : null;
                        } else {
                            $row[$column] = null;
                        }
                    } catch (\Exception $e) {
                        $row[$column] = null;
                    }
                } else {
                    // Handle regular columns
                    $value = $record->getAttribute($column);
                    
                    // Check if this is a tinyint field with constants
                    if (isset($columnTypes[$column]) && $columnTypes[$column] === 'tinyint') {
                        $constantValue = $this->getTinyintConstantValue($modelClass, $column, $value);
                        $row[$column] = $constantValue ?? $value;
                } else {
                        $row[$column] = $value;
                    }
                }
            }
            
            $exportData[] = $row;
        }
        
        return $exportData;
    }
    
    /**
     * Apply QueryBuilder filter to query
     */
    private function applyQueryBuilderFilter($query, array $filterState)
    {
        if (empty($filterState)) {
            return;
        }
        
        // Check if this is a QueryBuilder filter with 'rules' structure
        if (isset($filterState['rules']) && is_array($filterState['rules'])) {
            $this->applyQueryBuilderRules($query, $filterState['rules']);
            return;
        }
        
        // Fallback to simple constraint handling
        foreach ($filterState as $constraint) {
            if (!is_array($constraint) || empty($constraint)) {
                continue;
            }
            
            $this->applySingleConstraint($query, $constraint);
        }
    }
    
    /**
     * Apply QueryBuilder rules (nested structure)
     */
    private function applyQueryBuilderRules($query, array $rules)
    {
        foreach ($rules as $rule) {
            if (!is_array($rule)) {
                continue;
            }
            
            // Check if this is a constraint with data
            if (isset($rule['data'])) {
                $constraint = $rule['data'];
                
                // Handle different constraint types
                if (isset($constraint['column'])) {
                    $this->applySingleConstraint($query, $constraint);
                } elseif (isset($constraint['relationship'])) {
                    // Handle relationship constraints
                    $this->applyRelationshipConstraint($query, $constraint);
                }
            }
        }
    }
    
    /**
     * Apply a single constraint to query
     */
    private function applySingleConstraint($query, array $constraint)
    {
        $column = $constraint['column'] ?? $constraint['attribute'] ?? null;
        $operator = $constraint['operator'] ?? null;
        $value = $constraint['value'] ?? $constraint['settings']['value'] ?? null;
        
        if (!$column) {
            return;
        }
        
        // Log the constraint being applied
        Log::info('Applying constraint', [
            'column' => $column,
            'operator' => $operator,
            'value' => $value
        ]);
        
        try {
            // If no operator, check for special constraint types
            if (!$operator) {
                if (isset($constraint['settings']['isNull'])) {
                    $query->whereNull($column);
                    return;
                }
                if (isset($constraint['settings']['isNotNull'])) {
                    $query->whereNotNull($column);
                    return;
                }
                return;
            }
            
            // Apply constraint based on operator
            match ($operator) {
                'equals', '=', 'is' => $query->where($column, '=', $value),
                'notEquals', '!=', 'isNot' => $query->where($column, '!=', $value),
                'contains' => $query->where($column, 'like', "%{$value}%"),
                'startsWith' => $query->where($column, 'like', "{$value}%"),
                'endsWith' => $query->where($column, 'like', "%{$value}"),
                'greaterThan', '>', 'isAfter' => $query->where($column, '>', $value),
                'lessThan', '<', 'isBefore' => $query->where($column, '<', $value),
                'greaterThanOrEqual', '>=' => $query->where($column, '>=', $value),
                'lessThanOrEqual', '<=' => $query->where($column, '<=', $value),
                'isFilled' => $query->whereNotNull($column),
                'isBlank' => $query->whereNull($column),
                'isTrue' => $query->where($column, true),
                'isFalse' => $query->where($column, false),
                default => null
            };
        } catch (\Exception $e) {
            Log::warning('Failed to apply constraint', [
                'column' => $column,
                'operator' => $operator,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    /**
     * Apply relationship constraint to query
     */
    private function applyRelationshipConstraint($query, array $constraint)
    {
        $relationship = $constraint['relationship'] ?? null;
        $value = $constraint['settings']['values'] ?? [];
        
        if (!$relationship || empty($value)) {
            return;
        }
        
        Log::info('Applying relationship constraint', [
            'relationship' => $relationship,
            'values' => $value
        ]);
        
        try {
            $query->whereHas($relationship, function ($q) use ($value) {
                $q->whereIn('id', $value);
            });
        } catch (\Exception $e) {
            Log::warning('Failed to apply relationship constraint', [
                'relationship' => $relationship,
                'error' => $e->getMessage()
            ]);
        }
    }
    
    private function getAllRelationships($model)
    {
        $allRelations = [];
        
        try {
            $reflection = new \ReflectionClass($model);
            $modelInstance = new $model;
            foreach ($reflection->getMethods() as $method) {
                if ($method->isPublic() && $method->getNumberOfParameters() === 0) {
                    try {
                        $result = $method->invoke($modelInstance);
                        if ($result instanceof \Illuminate\Database\Eloquent\Relations\Relation) {
                            $allRelations[] = $method->name;
                        }
                    } catch (\Exception $e) {
                        // Skip methods that can't be invoked
                        continue;
                    }
                }
            }
        } catch (\Exception $e) {
            // If reflection fails, return empty array
            return [];
        }
        
        return $allRelations;
    }
    
    /**
     * Get column types from model
     */
    private function getModelColumnTypes($modelClass): array
    {
        try {
            $columns = \App\Support\Utils::getModelColumns($modelClass, true);
            $columnTypes = [];
            
            foreach ($columns as $column) {
                $columnTypes[$column['name']] = $column['type_name'];
            }
            
            return $columnTypes;
        } catch (\Exception $e) {
            return [];
        }
    }
    
    /**
     * Get tinyint constant value from model
     */
    private function getTinyintConstantValue($modelClass, string $columnName, $value)
    {
        try {
            $reflection = new ReflectionClass($modelClass);
            
            // Try different constant naming conventions
                        $candidateConstants = [
                str($columnName)->plural()->upper()->value(),
                str($columnName)->upper()->value(),
                str($columnName)->singular()->upper()->value(),
                str($columnName)->singular()->value(),
                str($columnName)->plural()->value(),
                        ];
                        
                        foreach ($candidateConstants as $candidateConstant) {
                            if ($reflection->hasConstant($candidateConstant)) {
                                $constantArray = $reflection->getConstant($candidateConstant);
                    
                    // Return the human-readable value if it exists
                    if (is_array($constantArray) && array_key_exists($value, $constantArray)) {
                        return $constantArray[$value];
                    }
                    
                                break;
                            }
                        }
        } catch (\Exception $e) {
            // If anything fails, return null to use original value
        }
        
        return null;
    }

    public function headings(): array
    {
        $headings = [];

        foreach ($this->report->header ?? [] as $header) {
            if (str_contains($header, '.')) {
                $parts = explode('.', $header);
                $relationName = $parts[0];
                $fieldName = $parts[1] ?? '';

                // Create a more descriptive heading
                $heading = __(pascalToTitle(Utils::translate($relationName)));
                if ($fieldName && $fieldName !== 'id') {
                    $heading .= ' - ' . Utils::translate($fieldName);
                }
                $headings[] = $heading;
            } else {
                $headings[] = __(pascalToTitle(Utils::translate($header)));
            }
        }

        return $headings;
    }

    public function properties(): array
    {
        return [
            'creator' => $this->report->createdBy->name ?? 'System',
            'lastModifiedBy' => $this->report->updatedBy->name ?? 'System',
            'title' => $this->fileName,
            'description' => $this->report->header_description,
            'subject' => $this->fileName,
        ];
    }

    private function applyPdfStyling(AfterSheet $event)
    {
        // Set RTL for PDF
        $event->sheet->setRightToLeft(true);

        // Set font for PDF that supports Persian
        $event->sheet->getStyle('A:Z')
            ->getFont()
            ->setName('DejaVu Sans')
            ->setSize(10);
        
        // Apply RTL alignment to all cells
        $event->sheet->getStyle('A:Z')->getAlignment()
            ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_RIGHT)
            ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
            ->setReadOrder(\PhpOffice\PhpSpreadsheet\Style\Alignment::READORDER_RTL);

        // Auto-size columns for better PDF display
        foreach (range('A', 'Z') as $column) {
            $event->sheet->getColumnDimension($column)->setAutoSize(true);
        }
        
        // Set page setup for RTL
        $event->sheet->getPageSetup()
            ->setOrientation(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::ORIENTATION_LANDSCAPE)
            ->setPaperSize(\PhpOffice\PhpSpreadsheet\Worksheet\PageSetup::PAPERSIZE_A4);
    }
}