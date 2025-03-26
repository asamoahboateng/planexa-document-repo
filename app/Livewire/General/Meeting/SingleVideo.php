<?php

namespace App\Livewire\General\Meeting;

use Livewire\Component;

class SingleVideo extends Component
{
    public $video_url;
    public function mount($url)
    {
        $this->video_url = $url;
    }

    public function render()
    {
        return view('livewire.general.meeting.single-video');
    }
}
