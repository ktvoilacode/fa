<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class ArchiveOldAttempts extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'data:archive-attempts
                            {--date=2024-01-01 : Archive data before this date}
                            {--dry-run : Show what would be archived without making changes}
                            {--verify-only : Only verify data integrity}
                            {--delete : Delete archived data from main table after copying}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Archive old attempts to attempts_archive table';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $cutoff = $this->option('date');
        $dryRun = $this->option('dry-run');
        $verifyOnly = $this->option('verify-only');
        $delete = $this->option('delete');

        $this->info("╔════════════════════════════════════════════════════════╗");
        $this->info("║     ARCHIVE OLD ATTEMPTS - BEFORE {$cutoff}        ║");
        $this->info("╚════════════════════════════════════════════════════════╝");
        $this->newLine();

        // Verify archive table exists
        if (!Schema::hasTable('attempts_archive')) {
            $this->error("❌ Archive table 'attempts_archive' does not exist!");
            $this->info("   Run: php artisan migrate");
            return 1;
        }

        // Count records to archive
        $this->info("📊 Analyzing data...");
        $toArchive = DB::table('attempts')
            ->where('created_at', '<', $cutoff)
            ->count();

        $currentArchive = DB::table('attempts_archive')->count();
        $mainTable = DB::table('attempts')->count();

        $this->table(
            ['Metric', 'Count'],
            [
                ['Main table (attempts)', number_format($mainTable)],
                ['To archive (before ' . $cutoff . ')', number_format($toArchive) . ' (' . round(($toArchive/$mainTable)*100, 1) . '%)'],
                ['Current archive table', number_format($currentArchive)],
                ['Main table after archive', number_format($mainTable - $toArchive)],
            ]
        );

        if ($toArchive == 0) {
            $this->warn("⚠️  No records found to archive before {$cutoff}");
            return 0;
        }

        // Show sample data
        $this->newLine();
        $this->info("📋 Sample data to be archived:");
        $sample = DB::table('attempts')
            ->where('created_at', '<', $cutoff)
            ->orderBy('created_at', 'desc')
            ->limit(5)
            ->get(['id', 'user_id', 'test_id', 'created_at']);

        $this->table(
            ['ID', 'User ID', 'Test ID', 'Created At'],
            $sample->map(fn($s) => [$s->id, $s->user_id, $s->test_id, $s->created_at])->toArray()
        );

        // Verify only mode
        if ($verifyOnly) {
            $this->info("✅ VERIFICATION MODE - Data analysis complete");
            return 0;
        }

        // Dry run mode
        if ($dryRun) {
            $this->warn("🔍 DRY RUN MODE - No changes will be made");
            $this->info("\nWhat would happen:");
            $this->info("  1. Copy {$toArchive} records to attempts_archive");
            $this->info("  2. Verify data integrity");
            if ($delete) {
                $this->info("  3. Delete {$toArchive} records from attempts table");
                $this->info("  4. Optimize attempts table");
            }
            return 0;
        }

        // Confirm action
        $this->newLine();
        if (!$this->confirm("⚠️  Archive " . number_format($toArchive) . " records?", false)) {
            $this->error("❌ Operation cancelled");
            return 1;
        }

        // Step 1: Copy data to archive
        $this->info("\n📦 Step 1: Copying data to archive table...");
        $bar = $this->output->createProgressBar(100);
        $bar->start();

        try {
            // Copy in chunks to avoid timeout
            $chunkSize = 5000;
            $copied = 0;

            DB::table('attempts')
                ->where('created_at', '<', $cutoff)
                ->orderBy('id')
                ->chunk($chunkSize, function ($attempts) use (&$copied, $bar, $toArchive) {
                    $data = $attempts->map(function($attempt) {
                        return (array) $attempt;
                    })->toArray();

                    DB::table('attempts_archive')->insertOrIgnore($data);

                    $copied += count($attempts);
                    $bar->setProgress(min(100, ($copied / $toArchive) * 100));
                });

            $bar->finish();
            $this->newLine(2);
            $this->info("✅ Copied records to archive");

        } catch (\Exception $e) {
            $this->error("\n❌ Error copying data: " . $e->getMessage());
            return 1;
        }

        // Step 2: Verify data integrity
        $this->info("\n🔍 Step 2: Verifying data integrity...");

        $verifyCount = DB::table('attempts_archive')->count();
        $expectedCount = $currentArchive + $toArchive;

        if ($verifyCount >= $expectedCount) {
            $this->info("✅ Verification passed: {$verifyCount} records in archive");
        } else {
            $this->error("❌ Verification failed!");
            $this->error("   Expected: {$expectedCount}, Found: {$verifyCount}");
            $this->error("   NOT proceeding with deletion!");
            return 1;
        }

        // Step 3: Delete from main table (if requested)
        if ($delete) {
            $this->newLine();
            if (!$this->confirm("⚠️  DELETE " . number_format($toArchive) . " records from main table?", false)) {
                $this->warn("Skipping deletion. Data archived but not removed from main table.");
                return 0;
            }

            $this->info("\n🗑️  Step 3: Deleting archived data from main table...");

            try {
                $deleted = DB::table('attempts')
                    ->where('created_at', '<', $cutoff)
                    ->delete();

                $this->info("✅ Deleted {$deleted} records from main table");

                // Step 4: Optimize table
                $this->info("\n⚙️  Step 4: Optimizing table...");
                DB::statement("OPTIMIZE TABLE attempts");
                $this->info("✅ Table optimized");

                // Final stats
                $newMainCount = DB::table('attempts')->count();
                $this->newLine();
                $this->info("╔════════════════════════════════════════════════════════╗");
                $this->info("║              ARCHIVING COMPLETE!                       ║");
                $this->info("╚════════════════════════════════════════════════════════╝");
                $this->table(
                    ['Metric', 'Count'],
                    [
                        ['Original main table', number_format($mainTable)],
                        ['Archived', number_format($deleted)],
                        ['New main table size', number_format($newMainCount)],
                        ['Archive table size', number_format($verifyCount)],
                        ['Space saved', round((($deleted/$mainTable)*100), 1) . '%'],
                    ]
                );

            } catch (\Exception $e) {
                $this->error("❌ Error deleting data: " . $e->getMessage());
                return 1;
            }
        } else {
            $this->newLine();
            $this->info("╔════════════════════════════════════════════════════════╗");
            $this->info("║         ARCHIVING COMPLETE (NO DELETION)               ║");
            $this->info("╚════════════════════════════════════════════════════════╝");
            $this->warn("Data copied to archive but NOT deleted from main table.");
            $this->info("To delete: php artisan data:archive-attempts --delete");
        }

        return 0;
    }
}
