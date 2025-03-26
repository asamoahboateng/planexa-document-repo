<?php

namespace App\Livewire\General\Meeting;

use App\Models\General\MeetingVideo;
use Filament\Forms\Concerns\InteractsWithForms;
use Filament\Forms\Contracts\HasForms;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Contracts\HasTable;
use Filament\Tables\Table;
use Illuminate\Support\Collection;
use Livewire\Component;
use \Filament\Tables\Concerns\InteractsWithTable;
use Livewire\WithPagination;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;

class VideoTranscript extends Component
{
    use WithPagination;

    public $meeting, $data = [], $search = "", $paginate_count = 5, $meeting_url;

    public  $listeners = ['gototimevideo'];

    public function mount(MeetingVideo $meetingVideo)
    {
        $this->meeting = $meetingVideo;
        $this->meeting_url = $this->meeting->embeddedurl();
        $this->data = Collection::make($this->meeting->fetchvideotranscript());
    }

    protected function getPaginatedData()
    {
        // Get the current page and paginate the collection
        $currentPage = Paginator::resolveCurrentPage();
        $perPage = 5; // Define how many items per page

        // Paginate the collection
        return new LengthAwarePaginator(
            $this->data->forPage($currentPage, $perPage), // Items for the current page
            $this->data->count(), // Total count of items
            $perPage, // Per page limit
            $currentPage, // Current page
            ['path' => Paginator::resolveCurrentPath()] // Path for pagination links
        );
    }

    public function gototimevideo($time)
    {
        $newUrl = $this->meeting->update_video_time($time);
        $this->meeting_url = $newUrl;
//        $this->emit('refreshComponent');

    }

    public function render()
    {
//        $paginatedData = $this->getPaginatedData();
//        return view('livewire.general.meeting.video-transcript',[
//            'collected_data' => $paginatedData
//        ])->extends('backend.layouts.main')->section('contents');

        // Filter the items based on the search term
        $filteredItems = collect($this->data)
            ->filter(function ($item) {
                return strpos(strtolower($item['text']), strtolower($this->search)) !== false;
            })
            ->values(); // Reindex the array

        // Paginate the filtered items
        // Paginate the filtered items
        $currentPage = Paginator::resolveCurrentPage();
        $perPage = $this->paginate_count; // Adjust the number 3 for pagination size
        $currentItems = $filteredItems->slice(($currentPage - 1) * $perPage, $perPage)->all();
        $paginatedItems = new LengthAwarePaginator(
            $currentItems,
            $filteredItems->count(),
            $perPage,
            $currentPage,
            ['path' => Paginator::resolveCurrentPath()]
        );

        return view('livewire.general.meeting.video-transcript', [
            'collected_data' => $paginatedItems,
            'playing_url' => $this->meeting_url
        ])->extends('backend.layouts.main')->section('contents');
    }
}
