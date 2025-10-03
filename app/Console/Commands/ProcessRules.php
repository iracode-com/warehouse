<?php

namespace App\Console\Commands;

use App\Services\RuleEngine;
use Illuminate\Console\Command;

class ProcessRules extends Command
{
    /**
     * The name and signature of the console command.
     */
    protected $signature = 'rules:process {--cleanup : Clean up old alerts} {--stats : Show statistics}';

    /**
     * The console command description.
     */
    protected $description = 'Process all active rules and generate alerts';

    /**
     * Execute the console command.
     */
    public function handle(RuleEngine $ruleEngine): int
    {
        $this->info('Starting rule processing...');

        if ($this->option('stats')) {
            $this->showStatistics($ruleEngine);
            return 0;
        }

        if ($this->option('cleanup')) {
            $this->cleanupAlerts($ruleEngine);
        }

        $processedCount = $ruleEngine->processRules();

        $this->info("Processed {$processedCount} rules successfully.");

        return 0;
    }

    /**
     * Show statistics about rules and alerts
     */
    protected function showStatistics(RuleEngine $ruleEngine): void
    {
        $stats = $ruleEngine->getStatistics();

        $this->info('Rule Engine Statistics:');
        $this->line('');

        $this->table(
            ['Metric', 'Count'],
            [
                ['Total Rules', $stats['total_rules']],
                ['Active Rules', $stats['active_rules']],
                ['Realtime Rules', $stats['realtime_rules']],
                ['Total Alerts', $stats['total_alerts']],
                ['Active Alerts', $stats['active_alerts']],
                ['Unread Alerts', $stats['unread_alerts']],
                ['Critical Alerts', $stats['critical_alerts']],
            ]
        );

        if (!empty($stats['alerts_by_type'])) {
            $this->line('');
            $this->info('Alerts by Type:');
            foreach ($stats['alerts_by_type'] as $type => $count) {
                $this->line("  {$type}: {$count}");
            }
        }

        if (!empty($stats['alerts_by_status'])) {
            $this->line('');
            $this->info('Alerts by Status:');
            foreach ($stats['alerts_by_status'] as $status => $count) {
                $this->line("  {$status}: {$count}");
            }
        }
    }

    /**
     * Clean up old alerts
     */
    protected function cleanupAlerts(RuleEngine $ruleEngine): void
    {
        $this->info('Cleaning up old alerts...');
        
        $deletedCount = $ruleEngine->cleanupAlerts(30);
        
        $this->info("Deleted {$deletedCount} old alerts.");
    }
}
