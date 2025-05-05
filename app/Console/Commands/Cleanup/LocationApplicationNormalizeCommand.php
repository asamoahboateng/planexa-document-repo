<?php

namespace App\Console\Commands\Cleanup;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class LocationApplicationNormalizeCommand extends Command
{
    /**
     * Cleanup and remove Buplcation location
     *
     * @var string
     */
    protected $signature = 'location:clean-duplicate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove location with the locations and Applications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $this->info('Starting duplicate location cleanup...');

        // Find duplicate locations based on lat and long
        $duplicates = DB::table('locations')
            ->select(
                'lat', 'long',
                DB::raw('COUNT(*) as count'),
                DB::raw('GROUP_CONCAT(id) as location_ids')
            )
            ->whereNotNull('lat')
            ->whereNotNull('long')
            ->where('lat', '!=', '')
            ->where('long', '!=', '')
            ->where('lat', '!=', '0')
            ->where('long', '!=', '0')
            ->groupBy('lat', 'long')
            ->having('count', '>', 1)
            ->get();

        if ($duplicates->isEmpty()) {
            $this->info('No duplicate locations found.');
            return;
        }

        $this->info("Found {$duplicates->count()} sets of duplicate locations.");

        foreach ($duplicates as $duplicate) {
            $this->processDuplicateSet($duplicate);
            echo ($duplicate->location_ids. PHP_EOL);
        }

        $this->info('Cleanup completed successfully.');
    }

    private function processDuplicateSet($duplicate)
    {
        // Get array of location IDs
        $locationIds = explode(',', $duplicate->location_ids);

        // Keep the first location ID and remove others
        $keepLocationId = array_shift($locationIds);
        $deleteLocationIds = $locationIds;

        $this->info("Processing duplicate set: Keeping location ID {$keepLocationId}, removing IDs " . implode(', ', $deleteLocationIds));

        try {
            DB::transaction(function () use ($keepLocationId, $deleteLocationIds) {
                // Delete the duplicate locations
                $deletedCount = DB::table('locations')
                    ->whereIn('id', $deleteLocationIds)
                    ->delete();

                $this->line("Deleted {$deletedCount} duplicate locations");
            });
        } catch (\Exception $e) {
            $this->error("Error processing duplicate set: " . $e->getMessage());
        }
    }
}
