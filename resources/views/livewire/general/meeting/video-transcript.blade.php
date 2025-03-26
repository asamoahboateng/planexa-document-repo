<div>
    {{-- Nothing in the world is as soft and yielding as water. --}}
{{--    <a href="{{ $playing_url }}">{{ $playing_url }}</a>--}}
    <div class="w-full w-100">
        <iframe
            height="450"
            style="width:100%"
            src="{{ $playing_url }}"
            title="YouTube video player" frameborder="0"
            allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
            referrerpolicy="strict-origin-when-cross-origin"
            allowfullscreen>
        </iframe>
    </div>

    <div class="divider"></div>

    <div class="bg-gray-50 p-5 mb-3 shadow">
        <div class="my-2 flex flex-row">
            <div class="basis-2/4 md:basis-1/3 my-auto text-end">
                <select class="select" wire:model.live="paginate_count">
                    <option>Pick a color</option>
                    <option value="5" {{ ($paginate_count == 5)?'selected':'' }}>5</option>
                    <option value="10" {{ ($paginate_count == 10)?'selected':'' }}>10</option>
                    <option value="15" {{ ($paginate_count == 15)?'selected':'' }}>15</option>
                    <option value="20" {{ ($paginate_count == 20)?'selected':'' }}>20</option>
                </select>
            </div>
            <div class="basis-2/4 md:basis-1/3 my-auto text-end">
                <label class="font-bold uppercase px-5">Enter a Text</label>
            </div>
            <div class="basis-1/4 md:basis-1/3">
                <input class=" input mr-0 input-sm" type="text" wire:model.live="search" placeholder="search for text">
            </div>
        </div>
    </div>

    {{ $search }}
    <div class="overflow-x-auto mb-5">

        <table class="table">
            <!-- head -->
            <thead>
            <tr>
                <th></th>
                <th>Start</th>
                <th>Duration</th>
                <th>Text</th>
                <th>#</th>
            </tr>
            </thead>
            <tbody>
            @foreach($collected_data as $dat)
                <tr class="bg-base-200">
{{--                    <th>{{ $loop->iteration  }}</th>--}}
                    <th>{{ ($collected_data->currentPage() - 1) * $collected_data->perPage() + $loop->iteration }}</th>
                    <td>{{ $dat['start'] }}</td>
                    <td>{{ $dat['duration'] }}</td>
                    <td>{{ $dat['text'] }}</td>
                    <td>
                        <button class="btn btn-xs btn-outline btn-secondary" wire:click.prevent="gototimevideo('{{ $dat['start'] }}')">@svg('heroicon-o-film', 'w-4 h-4') View</button>
                    </td>
                </tr>
            @endforeach

            </tbody>
        </table>
    </div>
    {{ $collected_data->links('vendor.livewire.tailwind') }}

</div>
