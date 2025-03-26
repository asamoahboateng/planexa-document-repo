<div class="space-y-4">
    <div class="grid grid-cols-1 gap-4">
        <h3>Meeting: {{ $record->meeting->name }}</h3>
        <h3>Location: {{ $record->location->location }}</h3>
        <h3>Location: {{ $record->url}}</h3>
{{--        <div class="card w-full">--}}
{{--            <div class="card-body w-full">--}}
{{--                --}}{{--                <embed src="{{ $record->url }}" type="application/pdf" style="width: 100%; height: 100vh;" />--}}
{{--                --}}{{--                @livewire(App\Livewire\General\Meeting\VideoTranscript::class, ['meetingVideo' => $record])--}}
{{--                <div style="width: 100%; height: 100vh;">--}}
{{--                    <iframe src="{{ $record->url }}" style="width: 100%; height: 100%;" frameborder="0"></iframe>--}}
{{--                </div>--}}
{{--            </div>--}}

{{--        </div>--}}
    </div>
</div>
