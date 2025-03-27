@extends('layouts.main')

@section('page_title', 'Home Page')

@section('styles')
    <style>
        .autocomplete-items {
            position: absolute;
            border: 1px solid #d4d4d4;
            border-top: none;
            z-index: 9999;
            top: 100%;
            left: 0;
            right: 0;
            background-color: white;
        }

        .autocomplete-items div {
            padding: 10px;
            cursor: pointer;
        }

        .autocomplete-items div:hover {
            background-color: #e9e9e9;
        }

        .search-container {
            position: relative;
            z-index: 1000;
        }

        .leaflet-popup-content {
            margin: 0;
            padding: 0;
        }

        .leaflet-popup-content-wrapper {
            padding: 0;
            border-radius: 8px;
        }

        .leaflet-popup {
            margin-bottom: 20px;
        }

        .custom-popup {
            padding: 16px;
            max-width: 300px;
        }

        .copy-button {
            background-color: #3b82f6;
            color: white;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
            transition: background-color 0.2s;
        }

        .copy-button:hover {
            background-color: #2563eb;
        }
    </style>
@endsection

@section('contents')
    <div class="container mx-auto mt-[8vh] md:px-[10vw] lg:px-[5vw]">
        <div class="flex flex-col md:flex-row gap-4 md:items-start items-center mt-0" id="mainBody">
            <div class="md:w-1/3 w-80 min-h-3">

                <div class="search-container mb-4">
                    <input type="text"
                           id="searchInput"
                           class="w-full p-2 border rounded-lg shadow-sm"
                           placeholder="Search location in Canada...">
                    <div id="searchResults" class="autocomplete-items"></div>
                </div>
                <div class="mt-4">
                    <div id="resultsCount" class="text-gray-600 mb-3"></div>
                    <div id="resultsCards" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4"></div>
                </div>
            </div>
            <div class="md:w-2/3 w-80 min-h-3">
                <div class="w-100">
                    <div id="map" class="w-full h-[800px] rounded-lg shadow-lg"></div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Canada boundaries
        const CANADA_BOUNDS = {
            north: 83.0,  // Northern limit
            south: 41.7,  // Southern limit
            west: -141.0, // Western limit
            east: -52.0   // Eastern limit
        };

        // Initial coordinates (centered on Canada)
        const initialCoordinates = {
            lat: {{ $coordinates['lat'] ?? 56.130366 }},
            lng: {{ $coordinates['lng'] ?? -106.346771 }},
            name: '{!! $coordinates['name'] ?? "Canada" !!}',
            address: '{!! $coordinates['address'] ?? "" !!}',
            type: '{!! $coordinates['type'] ?? "" !!}'
        };

        // Initialize map
        const map = L.map('map', {
            minZoom: 3,
            maxBounds: [
                [CANADA_BOUNDS.south, CANADA_BOUNDS.west], // Southwest
                [CANADA_BOUNDS.north, CANADA_BOUNDS.east]  // Northeast
            ]
        }).setView([initialCoordinates.lat, initialCoordinates.lng], 4);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Current marker reference
        let currentMarker = null;

        // Create popup content
        function createPopupContent(result) {
            return `
                <div class="custom-popup">
                    <h3 class="font-bold text-lg mb-3">${result.display_name}</h3>
                    <div class="grid gap-2 text-sm mb-3">
                        <div>
                            <span class="font-semibold">Latitude:</span>
                            <span>${result.lat}</span>
                        </div>
                        <div>
                            <span class="font-semibold">Longitude:</span>
                            <span>${result.lon}</span>
                        </div>
                        ${result.type ? `
                        <div>
                            <span class="font-semibold">Type:</span>
                            <span class="capitalize">${result.type}</span>
                        </div>
                        ` : ''}
                    </div>
                    <button onclick="copyLocation(${result.lat}, ${result.lon})"
                            class="copy-button">
                        Copy Coordinates
                    </button>
                </div>
            `;
        }

        // Copy coordinates function
        function copyLocation(lat, lon) {
            navigator.clipboard.writeText(`${lat}, ${lon}`)
                .then(() => alert('Coordinates copied to clipboard!'))
                .catch(err => console.error('Failed to copy:', err));
        }

        // Debounce function
        function debounce(func, wait) {
            let timeout;
            return function(...args) {
                clearTimeout(timeout);
                timeout = setTimeout(() => func.apply(this, args), wait);
            };
        }

        // Search functionality
        const searchInput = document.getElementById('searchInput');
        const searchResults = document.getElementById('searchResults');

        // Add event listeners
        const performSearch = debounce(async (query) => {
            if (query.length < 3) {
                searchResults.innerHTML = '';
                return;
            }

            try {
                // Get autocomplete results from Nominatim
                const nominatimResponse = await fetch(
                    `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&countrycodes=ca&addressdetails=1`
                );
                const nominatimData = await nominatimResponse.json();

                // Display autocomplete results
                searchResults.innerHTML = '';
                nominatimData.slice(0, 5).forEach(result => {
                    const div = document.createElement('div');
                    div.classList.add('p-2', 'hover:bg-gray-100', 'cursor-pointer');
                    div.textContent = result.display_name;

                    div.addEventListener('click', async () => {
                        const lat = parseFloat(result.lat);
                        const lon = parseFloat(result.lon);

                        if (lat > CANADA_BOUNDS.south && lat < CANADA_BOUNDS.north &&
                            lon > CANADA_BOUNDS.west && lon < CANADA_BOUNDS.east) {

                            searchInput.value = '';
                            searchResults.innerHTML = '';

                            try {
                                const dbResponse = await fetch(`/search-locations?lat=${lat}&lng=${lon}`);
                                const locations = await dbResponse.json();

                                // Update results count
                                const resultsCount = document.getElementById('resultsCount');
                                resultsCount.textContent = `Showing  location${locations.length !== 1 ? 's' : ''} near ${result.display_name}`;

                                // Update results cards container style
                                const resultsCards = document.getElementById('resultsCards');
                                resultsCards.className = 'flex flex-col space-y-4'; // Stack cards vertically
                                resultsCards.innerHTML = '';

                                if (currentMarker) {
                                    map.removeLayer(currentMarker);
                                }

                                map.setView([lat, lon], 13);

                                // Add search location marker
                                currentMarker = L.marker([lat, lon])
                                    .addTo(map)
                                    .bindPopup(`<div class="custom-popup">
                                    <h3 class="font-bold text-lg mb-3">Search Location</h3>
                                    <p>${result.display_name}</p>
                                    ${locations.length === 0 ?
                                        '<p class="text-red-500 mt-2">No locations found within 2.5km radius</p>' :
                                        ''}
                                </div>`)
                                    .openPopup();

                                if (locations.length > 0) {
                                    // Add all markers to map
                                    locations.forEach(location => {
                                        const marker = L.marker([location.lat, location.lng])
                                            .addTo(map)
                                            .bindPopup(
                                                `<div class="custom-popup">
                                                <h3 class="font-bold text-lg mb-3">${location.location}</h3>
                                                <p>Coordinates: ${location.lat}, ${location.lng}</p>
                                                <p>Distance: ${location.distance.toFixed(2)} km</p>
                                                <a href="/location/${location.id}"
                                                   class="btn btn-outline btn-primary text-white px-4 py-2 rounded inline-block mt-2">
                                                    View Details
                                                </a>
                                            </div>`,
                                                {
                                                    maxWidth: 350,
                                                    minWidth: 300
                                                }
                                            );
                                    });

                                    // Only show first 3 cards
                                    locations.slice(0, 3).forEach(location => {
                                        const card = document.createElement('div');
                                        card.className = 'bg-white rounded-lg shadow p-4 w-full';
                                        card.innerHTML = `
                                        <h3 class="font-bold text-lg mb-2">${location.location}</h3>
                                        <p class="text-gray-600 mb-2">Distance: ${location.distance.toFixed(2)} km</p>
                                        <p class="text-sm text-gray-500 mb-3">Coordinates: ${location.lat}, ${location.lng}</p>
                                        <a href="/location/${location.id}"
                                           class="btn btn-outline btn-primary text-center w-full py-auto align-middle">
                                            View Details
                                        </a>
                                    `;
                                        resultsCards.appendChild(card);
                                    });
                                }
                            } catch (error) {
                                console.error('Database search failed:', error);
                                const resultsCount = document.getElementById('resultsCount');
                                resultsCount.innerHTML = '<span class="text-red-500">Error searching locations</span>';
                                resultsCards.innerHTML = '';

                                currentMarker.bindPopup(`<div class="custom-popup">
                                <h3 class="font-bold text-lg mb-3">Search Location</h3>
                                <p>${result.display_name}</p>
                                <p class="text-red-500 mt-2">Error searching nearby locations</p>
                            </div>`).openPopup();
                            }
                        } else {
                            alert('Location must be within Canada');
                        }
                    });
                    searchResults.appendChild(div);
                });
            } catch (error) {
                console.error('Search failed:', error);
                searchResults.innerHTML = '<div class="p-2 text-red-500">Search failed. Please try again.</div>';
            }
        }, 300);
        searchInput.addEventListener('input', (e) => {
            performSearch(e.target.value);
        });

        // Close search results when clicking outside
        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.innerHTML = '';
            }
        });

        // Set initial marker if coordinates exist
        if (initialCoordinates.lat && initialCoordinates.lng) {
            currentMarker = L.marker([initialCoordinates.lat, initialCoordinates.lng])
                .addTo(map)
                .bindPopup(createPopupContent({
                    lat: initialCoordinates.lat,
                    lon: initialCoordinates.lng,
                    display_name: initialCoordinates.name,
                    type: initialCoordinates.type
                }))
                .openPopup();
        }
    </script>
@endsection
