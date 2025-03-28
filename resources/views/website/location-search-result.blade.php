@extends('layouts.main')

@section('page_title', '{{ $location->location  }}')

@section('styles')
@endsection

@section('contents')
    <div class="container mx-auto mt-[8vh] md:px-[10vw] lg:px-[5vw]">
        <div class="flex flex-col md:flex-row gap-4 md:items-start items-center mt-0" id="mainBody">
            <div class="md:w-1/3 w-80 min-h-[300px] md:border-r-[4px] md:border-red-400 md:pr-4">

                <div class="search-container mb-4 ">
                    <h3 class="font-semibold lg:text-3xl md:text-xl">{{ $location->location }}</h3>
                    <p class="font-light">{{ $location->postal_code }}</p>
                </div>
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
                    @foreach($location->applications as $application)
                        @if($application->meeting()->count() > 0)
                            <div class="card shadow bg-gray-50 border-radius-4 mb-4">
                                <div class="card-body">
                                    <h3 class="text-xl"><a>{{ $application->file_number }}</a></h3>
                                    <div class="divider"></div>
                                    <p class="font-bold flex gap-2 items-center">
                                        <span>@svg('heroicon-o-calendar', 'w-6 w-h')</span>
                                        <span>{{ $application->meeting->name  }}</span>
                                        <span>@svg('heroicon-o-clock', 'w-6 w-h')</span>
                                        <span>{{ $application->meeting->hearing_time  }}</span>
                                    </p>
                                    <p class="font-light flex gap-2 items-center">
                                        <span>@svg('heroicon-o-map', 'w-6 w-h')</span>
                                        <span>{{ $application->meeting->district  }}, </span>
                                        <span>{{ $application->meeting->governing_committee  }}</span>
                                    </p>
                                    <p class="font-light flex gap-2 items-center">
                                        <span>@svg('heroicon-o-users', 'w-6 w-h')</span>
                                        <span>{{ $application->meeting->hearing_location  }}</span>
                                    </p>
                                    <a  href="{{ route('location.application', [ $location->id, $application->id]) }}" class="btn btn-md btn-outline btn-primary w-[100px] mr-0 text-end float-end mx-auto"> View </a>
                                </div>
                            </div>
                        @endif
                    @endforeach
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
