<div class="space-y-4">
    <div class="grid grid-cols-1 gap-4">
        <h3>{{ $record->video_title }}</h3>
        <div class="card w-full">
            <div class="card-body w-full">
                @livewire(App\Livewire\General\Meeting\VideoTranscript::class, ['meetingVideo' => $record])
            </div>
        </div>
    </div>
</div>
