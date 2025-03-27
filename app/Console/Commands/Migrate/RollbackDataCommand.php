<?php

namespace App\Console\Commands\Migrate;

use App\Models\General\Application;
use App\Models\General\Location;
use App\Models\General\Meeting;
use App\Models\General\MeetingVideo;
use Illuminate\Console\Command;

class RollbackDataCommand extends Command
{
    /**
     * Delete meeting data related to a district
     *
     * @var string
     */
    protected $signature = 'meeting:delete-bulk {district : name of district}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete bulk meeting based on a district';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        //
        $district = $this->argument('district');
        switch ($district) {
            case "1":
                $districtname = "North York";
                break;
            case "2":
                $districtname = "Scarborough";
                break;
            case "3":
                $districtname = "Etobicoke York";
                break;
            case "4":
                $districtname = "Toronto & East York";
                break;
        }

        $countlocation = Meeting::where('district', $districtname)->pluck('id')->toArray();

        Application::whereIn('meeting_id', $countlocation)->delete();
        MeetingVideo::whereIn('meeting_id', $countlocation)->delete();
        Meeting::where('district', $districtname)->delete();

//        dd('here');
        $this->info(count($countlocation). ' Deleted bulkly success!');
    }
}
