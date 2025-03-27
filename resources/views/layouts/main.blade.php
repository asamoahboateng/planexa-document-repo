<!DOCTYPE html>
<html data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }} | @yield('page_title')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
    {{--    <link href="/css/filament/filament/forms/forms.css" rel="stylesheet" />--}}
    {{--    @vite(['resources/css/backend.css', 'resources/js/backend.js'])--}}
    @filamentStyles
    @vite('resources/css/backend.css')
    @livewireStyles
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    @yield('styles')

</head>
<body class="min-h-screen bg-center bg-repeat bg-sky-500/500 bg-opacity-50 ">

<div class="drawer bg-white-100">
    <input id="my-drawer-3" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content flex flex-col">

        <!-- Navbar -->
        <div class="navbar bg-white text-blue-500 lg:px-[100px]">
            <div class="navbar-start">
                <label for="my-drawer-3" aria-label="open sidebar" class="btn btn-square btn-ghost lg:hidden">
                    <div class="">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /> </svg>
                        </div>
                    </div>
                </label>
                <a href="{{ route('home') }}" class="xl:text-xl">{{ config('app.name') }}</a>
            </div>
            <div class="navbar-end hidden lg:flex">
                <ul class="menu menu-horizontal px-1 text-lg">
                    <li class="px-4 mx-3">
                        <a class="text-lg font-semibold hover:bg-inherit hover:border-b-4 hover:border-purple-500 hover:border-bottom-100 px-4">Home</a>
                    </li>
                    <li class="px-4 mx-3">
                        <a class="text-lg font-semibold hover:bg-inherit hover:border-b-4 hover:border-purple-500 hover:border-bottom-100 px-4">Contact Us</a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Page content here -->
        <div class="relative w-full h-[90vh]">
            <div class="absolute bottom-0 left-0 w-1/2 h-full bg-cover bg-center opacity-5" style="background-image: url('/images/bg1.jpg'); z-index: -1;"></div>
            <!-- Other content goes here -->
            <div class="relative z-10">
                <!-- front title header-->
                <div class="mx-auto text-center mt-[5vh]" id="front-title">
                    <h1 class="font-bold lg:text-5xl md:text-3xl text-indigo-400">{{ config('app.name') }}</h1>
                    <p class="font-light">{{ config('app.subtitle') }}</p>
                </div>

            </div>

            <!-- main body-->
            @yield('contents')

        </div>
    </div>

    <div class="drawer-side">
        <label for="my-drawer-3" aria-label="close sidebar" class="drawer-overlay"></label>
        <ul class="menu bg-base-200 text-base-content min-h-full w-80 p-3">
            <li class="text-center mx-auto mb-4">
                <a class="text-xl text-primary text-center"> <h3 class="text-center">Planexa</h3></a>
            </li>
            <li class="my-1">
                <a href="{{ route('backend.dashboard') }}" class="text-lg"> @svg('heroicon-o-home', 'w-6 h-6') Home</a>
            </li>

        </ul>
    </div>


</div>

@livewireScripts
@yield('scripts')
@vite('resources/js/backend.js')
</body>
</html>
