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
</head>
<body class="bg-gray-100">
<div class="flex h-screen">
    <!-- Sidebar -->
    <aside class="w-64 bg-blue-900 text-white p-5 hidden md:block">
        <h2 class="text-xl font-bold mb-4">Dashboard</h2>
        <nav>
            <ul>
                <li class="mb-2"><a href="#" class="block py-2 px-4 hover:bg-blue-700 rounded">Home</a></li>
                <li class="mb-2"><a href="#" class="block py-2 px-4 hover:bg-blue-700 rounded">Profile</a></li>
                <li class="mb-2"><a href="#" class="block py-2 px-4 hover:bg-blue-700 rounded">Settings</a></li>
            </ul>
        </nav>
    </aside>

    <div class="flex-1 flex flex-col">
        <!-- Top Bar -->
        <header class="bg-white shadow p-4 flex justify-between items-center">
            <button class="md:hidden text-blue-900" onclick="toggleSidebar()">☰</button>
            <h1 class="text-lg font-semibold">Location Search</h1>
            <div class="text-blue-900">User</div>
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
                        ${result.address ? `
                        <div>
                            <span class="font-semibold">Address:</span>
                            <span>${Object.values(result.address).join(', ')}</span>
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
        const text = `${lat}, ${lon}`;
        navigator.clipboard.writeText(text)
            .then(() => {
                alert('Coordinates copied to clipboard!');
            })
            .catch(err => {
                console.error('Failed to copy coordinates:', err);
            });
    }

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
                `https://nominatim.openstreetmap.org/search?format=json&q=${encodeURIComponent(query)}&addressdetails=1`
            );
            const data = await response.json();

            searchResults.innerHTML = '';
            data.slice(0, 5).forEach(result => {
                const div = document.createElement('div');
                div.textContent = result.display_name;
                div.addEventListener('click', () => {
                    const lat = parseFloat(result.lat);
                    const lon = parseFloat(result.lon);
                    map.setView([lat, lon], 16);

                    // Remove existing marker
                    if (currentMarker) {
                        map.removeLayer(currentMarker);
                    }

                    // Create new marker with popup
                    currentMarker = L.marker([lat, lon])
                        .addTo(map)
                        .bindPopup(createPopupContent(result), {
                            maxWidth: 350,
                            minWidth: 300,
                            autoPan: true,
                            closeButton: true,
                            autoClose: false
                        })
                        .openPopup();

                    // Add click event to marker
                    currentMarker.on('click', function() {
                        this.openPopup();
                    });

                    // Update input and clear results
                    searchInput.value = result.display_name;
                    searchResults.innerHTML = '';
                });
                searchResults.appendChild(div);
            });
        } catch (error) {
            console.error('Search failed:', error);
            searchResults.innerHTML = '<div class="p-2 text-red-500">Search failed. Please try again.</div>';
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

    // Toggle sidebar function
    function toggleSidebar() {
        const sidebar = document.querySelector('aside');
        sidebar.classList.toggle('hidden');
    }
</script>
</body>
</html>
