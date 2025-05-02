<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Meeting Repository</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://unpkg.com/typed.js@2.0.16/dist/typed.umd.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <style>
        /* Base styles */
        body {
            font-family: 'Inter', sans-serif;
        }

        /* Hero section */
        .hero-overlay {
            background: linear-gradient(
                rgba(30, 58, 138, 0.8),
                rgba(30, 58, 138, 0.9)
            );
        }

        /* Navigation */
        .nav-link {
            padding: 0.5rem 1rem;
            color: rgb(243, 244, 246);
            transition: color 0.2s;
        }

        .nav-link:hover {
            color: white;
        }

        .active-link {
            border-bottom: 2px solid white;
        }

        /* Map container and controls */
        #mapContainer {
            position: relative;
            display: flex;
            width: 100%;
            min-height: 70vh;
        }

        #map {
            flex: 1;
            height: 70vh;
            z-index: 1;
        }

        /* Search section */
        .search-container {
            position: relative;
            z-index: 10;
        }

        #searchResults {
            position: absolute;
            width: 100%;
            background: white;
            border: 1px solid #e5e7eb;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
            max-height: 300px;
            overflow-y: auto;
            z-index: 50;
        }

        /* Results panel */
        #resultsContainer {
            width: 33.333333%;
            max-width: 28rem;
            background: white;
            border-left: 1px solid #e5e7eb;
            box-shadow: -2px 0 10px rgba(0, 0, 0, 0.1);
            overflow-y: auto;
            z-index: 40;
            height: 70vh;
        }

        /* Results cards */
        .results-card {
            background: white;
            border-radius: 0.5rem;
            padding: 1rem;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            transition: all 0.2s;
        }

        .results-card:hover {
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        /* Range slider */
        input[type="range"] {
            -webkit-appearance: none;
            width: 100%;
            height: 8px;
            background: #e5e7eb;
            border-radius: 9999px;
            outline: none;
            margin-top: 0.25rem;
        }

        input[type="range"]::-webkit-slider-thumb {
            -webkit-appearance: none;
            width: 20px;
            height: 20px;
            background: #1e3a8a;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            margin-top: -6px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
            transition: background-color 0.2s;
        }

        input[type="range"]::-moz-range-thumb {
            width: 20px;
            height: 20px;
            background: #1e3a8a;
            border: none;
            border-radius: 50%;
            cursor: pointer;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.2);
            transition: background-color 0.2s;
        }

        input[type="range"]::-webkit-slider-runnable-track {
            height: 8px;
            background: #e5e7eb;
            border-radius: 9999px;
            border: none;
        }

        input[type="range"]::-moz-range-track {
            height: 8px;
            background: #e5e7eb;
            border-radius: 9999px;
            border: none;
        }

        input[type="range"]:focus {
            outline: none;
        }

        input[type="range"]:focus::-webkit-slider-thumb {
            box-shadow: 0 0 0 2px white, 0 0 0 4px #1e3a8a;
        }

        input[type="range"]:focus::-moz-range-thumb {
            box-shadow: 0 0 0 2px white, 0 0 0 4px #1e3a8a;
        }

        input[type="range"]:hover::-webkit-slider-thumb {
            background: #1e40af;
        }

        input[type="range"]:hover::-moz-range-thumb {
            background: #1e40af;
        }

        /* Leaflet customization */
        .leaflet-popup-content-wrapper {
            border-radius: 0.5rem;
            padding: 0;
        }

        .leaflet-popup-content {
            margin: 0;
            padding: 1rem;
        }

        .leaflet-container {
            font-family: 'Inter', sans-serif;
        }

        /* Mobile responsiveness */
        @media (max-width: 768px) {
            #mapContainer {
                flex-direction: column;
            }

            #map {
                width: 100%;
                height: 50vh;
            }

            #resultsContainer {
                width: 100%;
                max-width: none;
                position: fixed;
                top: 4rem;
                right: 0;
                bottom: 0;
                height: auto;
            }

            .search-container {
                z-index: 20;
            }
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navigation -->
    <nav id="navbar" class="fixed w-full z-50 transition-colors duration-300">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <div class="flex-shrink-0">
                    <img src="{{ url('/images/planexa-logo.png') }}" alt="planexa" style="width: 50px">
                    <span class="text-white text-xl font-bold">CMR System 2</span>
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

    <!-- Footer -->
    <footer class="bg-blue-900 text-white py-8 mt-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <h3 class="text-xl font-bold mb-4">CMR System</h3>
                    <p class="text-gray-300">Your trusted platform for accessing community meeting documents and information.</p>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Quick Links</h3>
                    <ul class="space-y-2">
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">About Us</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Privacy Policy</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Terms of Service</a></li>
                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
                <div>
                    <h3 class="text-xl font-bold mb-4">Contact Us</h3>
                    <p class="text-gray-300">Email: info@cmrsystem.com</p>
                    <p class="text-gray-300">Phone: (123) 456-7890</p>
                </div>
            </div>
            <div class="mt-8 pt-8 border-t border-blue-800 text-center">
                <p class="text-gray-300">&copy; <?php echo date('Y'); ?> CMR System. All rights reserved.</p>
            </div>
        </div>
    </footer>
    <script>
        const navbar = document.getElementById('navbar');

        window.addEventListener('scroll', () => {
            if (window.scrollY > 50) {
                navbar.classList.add('bg-blue-900', 'shadow-md');
                // navbar.classList.remove('bg-blue-900');
            } else {
                // navbar.classList.add('bg-blue-900');
                navbar.classList.remove('bg-blue-900', 'shadow-md');
            }
        });
        // Constants and Variables
        const TORONTO_COORDS = {
            lat: 43.653225,
            lng: -79.383186
        };
        let currentMarker = null;
        let currentPage = 1;
        const resultsPerPage = 10;
        let allLocations = [];

        // Initialize map
        const map = L.map('map').setView([TORONTO_COORDS.lat, TORONTO_COORDS.lng], 11);

        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Add initial Toronto marker
        currentMarker = L.marker([TORONTO_COORDS.lat, TORONTO_COORDS.lng])
            .addTo(map)
            .bindPopup('Toronto, Ontario')
            .openPopup();

        // Initialize typed.js
        new Typed('#typed-text', {
            strings: [
                'Your Gateway to Community Engagement',
                'Access Meeting Documents Anywhere, Anytime',
                'Discover Local Development Projects',
                'Stay Informed About Your Community'
            ],
            typeSpeed: 50,
            backSpeed: 30,
            backDelay: 2000,
            loop: true
        });

        // Utility Functions
        function debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        function formatDate(dateString) {
            return new Date(dateString).toLocaleDateString('en-CA', {
                year: 'numeric',
                month: 'short',
                day: 'numeric'
            });
        }

        // Function to toggle results panel
        function toggleResultsPanel(show) {
            const resultsContainer = document.getElementById('resultsContainer');
            if (show) {
                resultsContainer.classList.remove('hidden');
            } else {
                resultsContainer.classList.add('hidden');
            }
            setTimeout(() => map.invalidateSize(), 400);
        }

        // DOM Elements
        const searchInput = document.getElementById('search');
        const searchResults = document.getElementById('searchResults');
        const radiusSlider = document.getElementById('radius');
        const radiusValue = document.getElementById('radiusValue');
        const searchButton = document.getElementById('searchButton');
        const resultsContainer = document.getElementById('resultsContainer');
        const resultsCount = document.getElementById('resultsCount');
        const resultsCards = document.getElementById('resultsCards');
        const paginationInfo = document.getElementById('paginationInfo');
        const prevButton = document.getElementById('prevPage');
        const nextButton = document.getElementById('nextPage');

        function displayResults() {
            const startIndex = (currentPage - 1) * resultsPerPage;
            const endIndex = Math.min(startIndex + resultsPerPage, allLocations.length);
            const paginatedLocations = allLocations.slice(startIndex, endIndex);

            resultsCards.innerHTML = '';

            paginatedLocations.forEach(location => {
                const card = document.createElement('div');
                card.className = 'results-card mb-4 p-4';
                card.innerHTML = `
                    <div class="space-y-3">
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">File Number:</span>
                            <span class="font-medium">${location.file_number || 'Empty'}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Application Number:</span>
                            <span class="font-medium">${location.application_number || 'Empty'}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Type:</span>
                            <span class="font-medium">${location.type || 'Empty'}</span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Status:</span>
                            <span class="px-2 py-1 bg-blue-100 rounded-full text-blue-700 text-xs">
                                ${location.status || 'Empty'}
                            </span>
                        </div>
                        <div class="flex items-center justify-between text-sm">
                            <span class="text-gray-600">Location:</span>
                            <span class="font-medium text-right">${location.location.location}</span>
                        </div>
                        <div class="pt-3 mt-3 border-t border-gray-200">
                            <a href="/location-application/${location.location_id}/${location.id}"
                               class="block w-full bg-blue-900 text-white text-center px-4 py-2 rounded hover:bg-blue-800 transition-colors">
                                View Details
                            </a>
                        </div>
                    </div>
                `;
                resultsCards.appendChild(card);

                // Add marker for this location
                if (location.location.lat && location.location.long) {
                    L.marker([location.location.lat, location.location.long])
                        .addTo(map)
                        .bindPopup(`
                            <div class="p-2 space-y-2">
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">File:</span>
                                    <span class="font-medium">${location.file_number || 'Empty'}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Application:</span>
                                    <span class="font-medium">${location.application_number || 'Empty'}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Type:</span>
                                    <span class="font-medium">${location.type || 'Empty'}</span>
                                </div>
                                <div class="flex items-center justify-between text-sm">
                                    <span class="text-gray-600">Status:</span>
                                    <span class="px-2 py-1 bg-blue-100 rounded-full text-blue-700 text-xs">
                                        ${location.status || 'Empty'}
                                    </span>
                                </div>
                                <div class="pt-2 mt-2 border-t border-gray-200">
                                    <a href="/location-application/${location.location_id}/${location.id}"
                                       class="text-blue-600 hover:underline text-sm block text-center">
                                        View Details
                                    </a>
                                </div>
                            </div>
                        `, {
                            maxWidth: 300
                        });
                }
            });

            const totalPages = Math.ceil(allLocations.length / resultsPerPage);
            paginationInfo.textContent = `Page ${currentPage} of ${totalPages}`;
            prevButton.disabled = currentPage === 1;
            nextButton.disabled = currentPage === totalPages;
        }
        // Search nearby locations
        async function searchNearbyLocations(lat, lng) {
            try {
                const radius = parseFloat(radiusSlider.value);
                const response = await fetch(`/search-locations?lat=${lat}&lng=${lng}&radius=${radius}`);

                if (!response.ok) throw new Error('Network response was not ok');

                allLocations = await response.json();

                // Clear existing markers except search location marker
                map.eachLayer((layer) => {
                    if (layer instanceof L.Marker && layer !== currentMarker) {
                        map.removeLayer(layer);
                    }
                });

                toggleResultsPanel(allLocations.length > 0);

                const countText = `Found ${allLocations.length} location${allLocations.length !== 1 ? 's' : ''} within ${radius}km`;
                document.querySelectorAll('#resultsCount').forEach(el => {
                    el.textContent = countText;
                });

                currentPage = 1;
                displayResults();

                // Adjust map bounds
                if (allLocations.length > 0) {
                    const bounds = L.latLngBounds([currentMarker.getLatLng()]);
                    allLocations.forEach(location => {
                        if (location.location && location.location.lat && location.location.long) {
                            bounds.extend([location.location.lat, location.location.long]);
                        }
                    });
                    map.fitBounds(bounds, { padding: [50, 50] });
                }
            } catch (error) {
                console.error('Failed to search nearby locations:', error);
                document.querySelectorAll('#resultsCount').forEach(el => {
                    el.innerHTML = '<span class="text-red-500">Error searching nearby locations</span>';
                });
                toggleResultsPanel(false);
            }
        }

        // Event Listeners
        radiusSlider.addEventListener('input', e => {
            radiusValue.textContent = e.target.value;
        });

        radiusSlider.addEventListener('change', () => {
            if (currentMarker) {
                const latlng = currentMarker.getLatLng();
                searchNearbyLocations(latlng.lat, latlng.lng);
            }
        });

        searchInput.addEventListener('input', debounce(async (e) => {
            const query = e.target.value;
            if (query.length < 3) {
                searchResults.classList.add('hidden');
                return;
            }

            try {
                const response = await fetch(
                    `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}+Toronto&countrycodes=ca&bounded=1`
                );
                const data = await response.json();

                searchResults.innerHTML = '';
                searchResults.classList.remove('hidden');

                data.slice(0, 5).forEach(result => {
                    const div = document.createElement('div');
                    div.classList.add('p-2', 'hover:bg-gray-100', 'cursor-pointer');
                    div.textContent = result.display_name;
                    div.addEventListener('click', () => handleLocationSelect(result));
                    searchResults.appendChild(div);
                });
            } catch (error) {
                console.error('Search failed:', error);
                searchResults.innerHTML = '<div class="p-2 text-red-500">Search failed. Please try again.</div>';
            }
        }, 300));

        async function handleLocationSelect(location) {
            searchInput.value = location.display_name;
            searchResults.classList.add('hidden');

            const lat = parseFloat(location.lat);
            const lng = parseFloat(location.lon);

            if (currentMarker) {
                map.removeLayer(currentMarker);
            }

            currentMarker = L.marker([lat, lng])
                .addTo(map)
                .bindPopup(location.display_name)
                .openPopup();

            map.setView([lat, lng], 14);
            await searchNearbyLocations(lat, lng);
        }

        prevButton.addEventListener('click', () => {
            if (currentPage > 1) {
                currentPage--;
                displayResults();
            }
        });

        nextButton.addEventListener('click', () => {
            const totalPages = Math.ceil(allLocations.length / resultsPerPage);
            if (currentPage < totalPages) {
                currentPage++;
                displayResults();
            }
        });

        document.addEventListener('click', (e) => {
            if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
                searchResults.classList.add('hidden');
            }
        });
    </script>
</body>
</html>
