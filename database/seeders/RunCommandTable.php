<?php

namespace Database\Seeders;

use App\Models\Utility\RunCommand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RunCommandTable extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $commands = [
          ['id' => '1', 'name' => 'Import Data from Scarborough Meeting', 'command' => 'meeting:json-migrate' , 'argument' => 'http://192.3.155.50/prg/json_v2/scb_meetings_data.json'],
          ['id' => '2', 'name' => 'Import Data from Etobicoke Meeting', 'command' => 'meeting:json-migrate' , 'argument' => 'http://192.3.155.50/prg/json_v2/etob_meetings_data.json'],
          ['id' => '3', 'name' => 'Import Data from North York Meeting', 'command' => 'meeting:json-migrate' , 'argument' => 'http://192.3.155.50/prg/json_v2/ny_meetings_data.json'],
          ['id' => '4', 'name' => 'Import Data from Toronto & East York Meeting', 'command' => 'meeting:json-migrate' , 'argument' => 'http://192.3.155.50/prg/json_v2/tey_meetings_data.json'],
          ['id' => '5', 'name' => 'Import Youtube Data', 'command' => 'meeting-video:json-migrate' , 'argument' => 'http://192.3.155.50/prg/json/youtube_video_list.json'],
          ['id' => '6', 'name' => 'Clean Up Address', 'command' => 'location:clean-address' , 'argument' => ''],
          ['id' => '7', 'name' => 'Update Location Postcode and Address', 'command' => 'location:update-details' , 'argument' => ''],
          ['id' => '8', 'name' => 'Remove location data North York', 'command' => 'meeting:delete-bulk' , 'argument' => 'North York'],
          ['id' => '9', 'name' => 'Remove location data Etobicoke York', 'command' => 'meeting:delete-bulk' , 'argument' => 'Etobicoke York'],
          ['id' => '10', 'name' => 'Remove location data Scarborough', 'command' => 'meeting:delete-bulk' , 'argument' => 'Scarborough'],
          ['id' => '11', 'name' => 'Remove location data Toronto & East York', 'command' => 'meeting:delete-bulk' , 'argument' => 'Toronto & East York'],
        ];

//        RunCommand::firstOrCreate($commands);
        foreach ($commands as $command) {
            RunCommand::firstOrCreate([
                'name' => $command['name'],
                'command' => $command['command'],
                'argument' => $command['argument']
            ]);
        }
    }
}
