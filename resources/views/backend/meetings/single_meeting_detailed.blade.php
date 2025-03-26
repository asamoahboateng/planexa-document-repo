<div class="space-y-4">
    <div class="grid grid-cols-1 gap-4">
        <h3>{{ $record->name }}</h3>
        <div class="card w-full">
            <div class="card-body w-full">
{{--                <embed src="{{ $record->url }}" type="application/pdf" style="width: 100%; height: 100vh;" />--}}
                {{--                @livewire(App\Livewire\General\Meeting\VideoTranscript::class, ['meetingVideo' => $record])--}}
                <div style="width: 100%; height: 100vh;">
                    <iframe src="{{ $record->url }}" style="width: 100%; height: 100%;" frameborder="0"></iframe>
                </div>
            </div>

            @foreach($record->videos as $video)
                <div class="card-body w-full">
                    @livewire(App\Livewire\General\Meeting\VideoTranscript::class, ['meetingVideo' => $video])
                </div>
            @endforeach
        </div>
    </div>
</div>
