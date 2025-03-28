<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Map with Search</title>
    <script src="https://cdn.tailwindcss.com"></script>

    <!-- Leaflet CSS and JS -->
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>

    <!-- Add Autocomplete CSS -->
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
    </style>
</head>
<body class="bg-gray-100">
<div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-blue-900 text-white p-5 hidden md:block">
        <!-- ... your existing sidebar code ... -->
    </aside>

    <div class="flex-1 flex flex-col">
        <!-- Top Bar -->
        <header class="bg-white shadow p-4 flex justify-between items-center">
            <!-- ... your existing header code ... -->
        </header>

        <!-- Main Content -->
        <main class="flex-1 p-6">
            <!-- Search Container -->
            <div class="search-container mb-4">
                <input type="text"
                       id="searchInput"
                       class="w-full p-2 border rounded-lg shadow-sm"
                       placeholder="Search location..."
                >
                <div id="searchResults" class="autocomplete-items"></div>
            </div>

            <!-- Map Container -->
            <div id="map" class="w-full h-[600px] rounded-lg shadow-lg"></div>
        </main>
    </div>
</div>

<script>
    // Initialize the map
    const map = L.map('map').setView([43.7812974, -79.4158993], 13);

    // Add OpenStreetMap tiles
    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
        maxZoom: 19,
        attribution: '© OpenStreetMap contributors'
    }).addTo(map);

    // Current marker reference
    let currentMarker = null;

    // Debounce function
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

    // Search functionality
    const searchInput = document.getElementById('searchInput');
    const searchResults = document.getElementById('searchResults');

    const performSearch = debounce(async (query) => {
        if (query.length < 3) {
            searchResults.innerHTML = '';
            return;
        }

        try {
            const response = await fetch(
                `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}`
            );
            const data = await response.json();

            // Display results
            searchResults.innerHTML = '';
            data.slice(0, 5).forEach(result => {
                const div = document.createElement('div');
                div.textContent = result.display_name;
                div.addEventListener('click', () => {
                    // Update map view
                    const lat = parseFloat(result.lat);
                    const lon = parseFloat(result.lon);
                    map.setView([lat, lon], 16);

                    // Update marker
                    if (currentMarker) {
                        map.removeLayer(currentMarker);
                    }
                    currentMarker = L.marker([lat, lon]).addTo(map);

                    // Clear search results and update input
                    searchInput.value = result.display_name;
                    searchResults.innerHTML = '';
                });
                searchResults.appendChild(div);
            });
        } catch (error) {
            console.error('Search failed:', error);
        }
    }, 300);

    // Add event listeners
    searchInput.addEventListener('input', (e) => {
        performSearch(e.target.value);
    });

    // Close search results when clicking outside
    document.addEventListener('click', (e) => {
        if (!searchInput.contains(e.target) && !searchResults.contains(e.target)) {
            searchResults.innerHTML = '';
        }
    });

    // Your existing toggleSidebar function
    function toggleSidebar() {
        const sidebar = document.querySelector('aside');
        sidebar.classList.toggle('hidden');
    }
</script>
</body>
</html>
