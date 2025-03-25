<!DOCTYPE html>
<html data-theme="light">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ config('app.name') }} | @yield('titile')</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600" rel="stylesheet" />

    <!-- Styles / Scripts -->
{{--    <link href="/css/filament/filament/forms/forms.css" rel="stylesheet" />--}}
{{--    @vite(['resources/css/backend.css', 'resources/js/backend.js'])--}}
    @filamentStyles
    @vite('resources/css/backend.css')
    @livewireStyles

</head>
<body class="bg-gray-100 min-h-screen">

    <div class="navbar bg-gray-50 text-blue-500 shadow-sm">
        <div class="navbar-start">
        </div>
        <div class="navbar-center">
            <a class="btn btn-ghost xl:text-xl">{{ config('app.name') }}</a>
        </div>
        <div class="navbar-end">
            <div class="dropdown dropdown-end">
                @if(auth()->user())
                    <div tabindex="0" role="button" class="btn btn-ghost btn-circle avatar">
                        <div class="w-10 rounded-full">
                            <img
                                alt="Tailwind CSS Navbar component"
                                src="https://img.daisyui.com/images/stock/photo-1534528741775-53994a69daeb.webp" />
                        </div>
                    </div>
                    <ul
                        tabindex="0"
                        class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                        <li>
                            <a class="justify-between">
                                Profile
                                <span class="badge">New</span>
                            </a>
                        </li>
                        <li><a>Settings</a></li>
                        <li><a>Logout</a></li>
                    </ul>
                @endif
            </div>
        </div>
    </div>
    <!-- Page content here -->
    @yield('contents')
    @livewireScripts
    @filamentScripts
    @vite('resources/js/backend.js')
</body>
</html>
