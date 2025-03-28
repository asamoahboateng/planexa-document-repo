<?php

namespace App\Livewire\Admin\Utility;

use Filament\Notifications\Notification;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Livewire\Component;

class RunCommand extends Component
{
    public $output;
    public function runlocationupdate()
    {
        // Run the Artisan command
        $output = [];
        $exitCode = null;

        Artisan::call('artisan location:clean-address');
        // Execute the command and capture the output
        exec('artisan location:clean-address', $output, $exitCode);

        // Set the output to be displayed in the component
        $this->output = implode("\n", $output);
    }

    public function resetCommands()
    {
        try{


            \App\Models\Utility\RunCommand::where(['status' => True])->update(['status' => '0']);

            $this->output = "Command Table Resetted !!";

        } catch (\Throwable $th) {
//            dd($th->getMessage());
            Log::error($th->getMessage());
        }
    }


    public function callcommand($commandID)
    {
        try{

            $commandrequest = \App\Models\Utility\RunCommand::find($commandID);
            if(in_array($commandrequest->id, ['11','10','9', '8'])){
                $retn = "";
                switch ($commandrequest->argument) {
                    case "North York":
                        $retn =  '1';
                        break;
                    case "Scarborough":
                        $retn =  '2';
                        break;
                    case "Etobicoke York":
                        $retn =  '3';
                        break;
                    case "Toronto and East York":
                        $retn =  '4';
                        break;
                }
//            dd($retn);
                Artisan::call($commandrequest->command . ' ' .$retn);
            }
            else {
                Artisan::call($commandrequest->command . ' ' .$commandrequest->argument);
            }


//            dd($commandrequest);
            $output = Artisan::output();
            $this->output = $output;
            Notification::make()->title('Saved successfully')
                ->success()
                ->duration(5000)
                ->send();
            sleep(2);

            if(!in_array($commandID, ['7', '6', '5','12'])){
                $commandrequest->update([
                    'status' => True
                ]);
            }

            if($commandID == '8') \App\Models\Utility\RunCommand::find(3)->update(['status' => '0']);
            if($commandID == '9') \App\Models\Utility\RunCommand::find(2)->update(['status' => '0']);
            if($commandID == '10') \App\Models\Utility\RunCommand::find(1)->update(['status' => '0']);
            if($commandID == '11') \App\Models\Utility\RunCommand::find(4)->update(['status' => '0']);

            if($commandID == '3') \App\Models\Utility\RunCommand::find(8)->update(['status' => '0']);
            if($commandID == '2') \App\Models\Utility\RunCommand::find(9)->update(['status' => '0']);
            if($commandID == '10') \App\Models\Utility\RunCommand::find(10)->update(['status' => '0']);
            if($commandID == '4') \App\Models\Utility\RunCommand::find(11)->update(['status' => '0']);



            Log::info('Command '. $commandrequest->name. ' ' .$output);

        } catch (\Throwable $th) {
//            dd($th->getMessage());
            Log::error($th->getMessage());
        }
    }

    public function render()
    {
        return view('livewire.admin.utility.run-command',[
            'commands'=> \App\Models\Utility\RunCommand::get()
        ])->extends('backend.layouts.main')->section('contents');;
    }


}
