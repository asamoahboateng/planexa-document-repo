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
