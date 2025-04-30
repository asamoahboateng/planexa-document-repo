@extends('website-new.layouts.main')

@section('contents')
    <!-- Navigation -->
    <nav id="navbar" class="fixed w-full z-50 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex-shrink-0">
                    <span class="text-white text-xl font-bold">CMR System</span>
                </div>
                <div class="hidden md:block">
                    <div class="flex items-center space-x-4">
                        <a href="#" class="nav-link active-link">Home</a>
                        <a href="#" class="nav-link">Meetings</a>
                        <a href="#" class="nav-link">Locations</a>
                        <a href="#" class="nav-link">About</a>
                        <a href="#" class="nav-link">Contact</a>
                    </div>
                </div>
                <div class="hidden md:block">
                    <button class="bg-white text-blue-900 px-4 py-2 rounded-lg font-semibold hover:bg-blue-50 transition-colors duration-200">
                        Sign In
                    </button>
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="relative h-[100dvh]">
        <div class="absolute inset-0 z-0">
            <img src="{{ url('images/map-background.jpg')}}"
                 alt="Map Background"
                 class="w-full h-full object-cover">
        </div>
        {{--        <div class="absolute inset-0 z-0">--}}
        {{--            <video class="w-full h-full object-cover" autoplay loop muted playsinline>--}}
        {{--                <source src="/storage/map-background.mp4" type="video/mp4">--}}
        {{--                <img src="/map-background.jpg" alt="Map Background" class="w-full h-full object-cover">--}}
        {{--            </video>--}}
        {{--        </div>--}}
        <div class="absolute inset-0 hero-overlay z-10"></div>
        <div class="relative z-20 h-full flex items-center justify-center">
            <div class="max-w-4xl mx-auto text-center px-4">
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6">
                    Community Meeting Repository
                </h1>
                <div class="text-xl md:text-2xl text-gray-200 mb-8">
                    <span id="typed-text"></span>
                </div>
                <div class="flex flex-col sm:flex-row gap-4 justify-center">
                    <button class="bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition-colors duration-200">
                        Browse Meetings
                    </button>
                    <button class="bg-white text-blue-900 px-8 py-3 rounded-lg font-semibold hover:bg-gray-100 transition-colors duration-200">
                        Search Locations
                    </button>
                </div>
            </div>
        </div>

        <!-- Downward Arrow -->
        <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2" style="z-index: 999 !important">
            <a href="#searchSection" class="text-white animate-bounce">
                @svg('heroicon-o-chevron-double-down', 'w-8 h-8')
            </a>
        </div>
    </div>

    <!-- Search Section -->
    <div id="searchSection" class="bg-white shadow-md py-6 search-container mb-8">
        <div class="max-w-7xl mx-auto px-4">
            <h2 class="text-2xl font-bold text-blue-900 mb-6">Find Community Meetings Near You</h2>
            <div class="flex flex-col md:flex-row gap-4 items-end">
                <div class="flex-1 relative">
                    <label for="search" class="block text-sm font-medium text-gray-700 mb-1">Location Search</label>
                    <input type="text"
                           id="search"
                           placeholder="Search Toronto locations..."
                           class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <div id="searchResults" class="hidden"></div>
                </div>
                <div class="w-full md:w-64">
                    <label for="radius" class="block text-sm font-medium text-gray-700 mb-1">
                        Search Radius: <span id="radiusValue">2.5</span>km
                    </label>
                    <input type="range"
                           id="radius"
                           min="0.5"
                           max="10"
                           step="0.5"
                           value="2.5"
                           class="w-full h-2 bg-gray-200 rounded-lg appearance-none cursor-pointer">
                </div>
                <button id="searchButton" class="bg-blue-900 text-white px-6 py-2 rounded-lg hover:bg-blue-800 transition-colors duration-200">
                    Search
                </button>
            </div>
            <div id="resultsCount" class="mt-4 text-gray-600"></div>
        </div>
    </div>

    <!-- Map and Results Section -->
    <div class="relative w-full">
        <div id="mapContainer" class="flex w-full">
            <div id="map" class="flex-1"></div>

            <!-- Results Container -->
            <div id="resultsContainer" class="hidden w-1/3 max-w-md border-l border-gray-200 bg-white shadow-lg">
                <div class="p-4">
                    <div class="text-gray-600 mb-4 font-semibold" id="resultsCount"></div>
                    <div id="resultsCards" class="space-y-4 mb-4"></div>
                    <!-- Pagination -->
                    <div class="flex items-center justify-between border-t border-gray-200 pt-4">
                        <div id="paginationInfo" class="text-sm text-gray-700"></div>
                        <div class="flex space-x-2">
                            <button id="prevPage" class="pagination-button">Previous</button>
                            <button id="nextPage" class="pagination-button">Next</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>


@endsection
