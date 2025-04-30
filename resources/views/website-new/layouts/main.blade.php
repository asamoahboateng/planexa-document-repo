<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Community Meeting Repository</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"/>
{{--    <script src="https://cdn.tailwindcss.com"></script>--}}
    <script src="https://unpkg.com/typed.js@2.0.16/dist/typed.umd.js"></script>
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @vite(['resources/css/website-new.css'])
    @stack('styles')
</head>
<body class="">

    @yield('contents')

    <!-- Footer -->
    <footer class="bg-blue-900 text-white py-4 mt-8">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
{{--            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">--}}
{{--                <div>--}}
{{--                    <h3 class="text-xl font-bold mb-4">CMR System</h3>--}}
{{--                    <p class="text-gray-300">Your trusted platform for accessing community meeting documents and information.</p>--}}
{{--                </div>--}}
{{--                <div>--}}
{{--                    <h3 class="text-xl font-bold mb-4">Quick Links</h3>--}}
{{--                    <ul class="space-y-2">--}}
{{--                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">About Us</a></li>--}}
{{--                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Privacy Policy</a></li>--}}
{{--                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Terms of Service</a></li>--}}
{{--                        <li><a href="#" class="text-gray-300 hover:text-white transition-colors">Contact</a></li>--}}
{{--                    </ul>--}}
{{--                </div>--}}
{{--                <div>--}}
{{--                    <h3 class="text-xl font-bold mb-4">Contact Us</h3>--}}
{{--                    <p class="text-gray-300">Email: info@cmrsystem.com</p>--}}
{{--                    <p class="text-gray-300">Phone: (123) 456-7890</p>--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="mt-8 border-t border-blue-800 text-center">
                <p class="text-gray-300">&copy; <?php echo date('Y'); ?> CMR System. All rights reserved.</p>
            </div>
        </div>
    </footer>

    @vite(['resources/js/website-new.js'])
@stack('scripts')
{{--<script>--}}
{{--   --}}
{{--</script>--}}
</body>
</html>
