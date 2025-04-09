@extends('layouts.main')

@section('page_title', '{{ $location->location  }}')

@section('styles')
@endsection

@section('contents')
    <div class="container mx-auto mt-[8vh] md:px-[10vw] lg:px-[5vw]">
        <div class="flex flex-col md:flex-row gap-4 md:items-start items-center mt-0" id="mainBody">

            <div class="md:w-1/3 w-80 min-h-[300px] md:border-r-[4px] md:border-red-400 md:pr-4">
                <!-- application information -->
                <table class="w-full mb-3">
                    <tbody>
                        <tr class="">
                            <td><span class="font-light">Location : </span> </td>
                            <td class="text-right mx-auto py-2">
                                <span  class="text-right font-bold text-lg">{{ $location->location }}</span>
                                <p class="font-light">{{ $location->postal_code }}</p>
                            </td>
                        </tr>
                        <tr class="py-3">
                            <td><span class="font-light">File  Number: </span> </td>
                            <td class="text-end py-2"><span  class="font-bold text-lg">{{ $application->file_number }}</span> </td>
                        </tr>
                        <tr class="py-3">
                            <td><span class="font-light">Application  Number: </span> </td>
                            <td class="text-end py-2"><span  class="font-bold text-lg">{{ $application->file_number }}</span> </td>
                        </tr>
                        <tr class="py-3">
                            <td><span class="font-light">Application Status: </span> </td>
                            <td class="text-end py-2"><span  class="font-bold text-lg">{{ $application->file_number }}</span> </td>
                        </tr>
                        <tr class="py-3">
                            <td colspan="2" class="py-2">
                                <span class="font-light">Description: </span>
                                <p>{{ $application->description }}</p>
                            </td>
                        </tr>
                        <tr class="py-3">
                            <td colspan="2" class="py-2">
                                <span class="font-light">Related Application: </span>
                            </td>
                        </tr>
                    </tbody>
                </table>

{{--                <div class="search-container mb-4 ">--}}
{{--                    <h3 class="font-semibold lg:text-2xl md:text-lg">{{ $location->location }}</h3>--}}
{{--                    <p class="font-light">{{ $location->postal_code }}</p>--}}
{{--                </div>--}}
                <div class="card shadow">
                    <div id="singleMap" class="w-full h-[300px] rounded-lg"></div>
                </div>
                <div class="mt-4">
                    <div id="resultsCount" class="text-gray-600 mb-3"></div>
                    <div id="resultsCards" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
                </div>

            </div>
            <div class="md:w-2/3 w-80 min-h-3">
                <div class="w-100">

                    <div class="card-body w-full pt-0">
                        <h2 class="text-lg font-bold">{{ $meeting->name }}</h2>
                        <h2 class="text-lg font-light">{{  $meeting->district .', '. $meeting->governing_committee }}</h2>
                        <hr>
                        <div class="w-full w-100">
                            <iframe
                                height="450"
                                style="width:100%"
                                src="{{ $application->applicationVideo()['updated_video'] }}"
                                title="YouTube video player" frameborder="0"
                                allow="accelerometer; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share"
                                referrerpolicy="strict-origin-when-cross-origin"
                                allowfullscreen>
                            </iframe>
                        </div>
                    </div>

                    <p>
                        <a class="text-lg font-light flex items-center hover:font-bold hover:bg-green-50 text-blue-500" href="{{ $application->url }}" target="_blank">
                            <span>@svg('heroicon-o-arrow-right', 'w-4 h-4')</span> &nbsp;<span>View Document on Official Site</span>
                        </a>
                    </p>
                    <p>
                        <a class="text-lg font-light flex items-center hover:font-bold hover:bg-green-50 text-blue-500" href="{{ $application->applicationVideo()['video']['url'] }}" target="_blank">
                            <span>@svg('heroicon-o-arrow-right', 'w-4 h-4')</span> &nbsp;<span>Watch Full Video</span>
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Example for Toronto coordinates
        const lat = {{ $location->lat ?? 43.7812974 }};
        const lng = {{ $location->long ?? -79.4158993 }};

        const map = L.map('singleMap');

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        map.setView([lat, lng], 13);

        const marker = L.marker([lat, lng])
            .addTo(map)
            .bindPopup('{{ $location->location }}')
            .openPopup();
    </script>
@endsection
