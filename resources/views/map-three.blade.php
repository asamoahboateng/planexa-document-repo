<!DOCTYPE html>
<html lang="en">
<!-- ... (previous head content remains the same) ... -->

<body class="bg-gray-100">
    <!-- ... (previous body structure remains the same until main content) ... -->

    <!-- Pass PHP variables to JavaScript -->
    <script>
        // Get coordinates from PHP
        const initialCoordinates = {
            lat: {{ $coordinates['lat'] ?? 43.7812974 }},
            lng: {{ $coordinates['lng'] ?? -79.4158993 }},
            name: '{{ $coordinates['name'] ?? 'Default Location' }}',
            address: '{{ $coordinates['address'] ?? '' }}',
            type: '{{ $coordinates['type'] ?? '' }}'
        };

        // Initialize the map with PHP coordinates
        const map = L.map('map').setView([initialCoordinates.lat, initialCoordinates.lng], 13);

        // Add OpenStreetMap tiles
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 19,
            attribution: '© OpenStreetMap contributors'
        }).addTo(map);

        // Current marker reference
        let currentMarker = null;

        // Create initial marker if coordinates exist
        if (initialCoordinates.lat && initialCoordinates.lng) {
            currentMarker = L.marker([initialCoordinates.lat, initialCoordinates.lng])
                .addTo(map)
                .bindPopup(createPopupContent({
                    lat: initialCoordinates.lat,
                    lon: initialCoordinates.lng,
                    display_name: initialCoordinates.name,
                    type: initialCoordinates.type,
                    address: {
                        full: initialCoordinates.address
                    }
                }), {
                    maxWidth: 350,
                    minWidth: 300,
                    autoPan: true,
                    closeButton: true,
                    autoClose: false
                })
                .openPopup();
        }

        // Rest of your JavaScript code remains the same...
    </script>
</body>
</html>
