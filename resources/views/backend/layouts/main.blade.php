<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-theme="light">
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
    <div class="drawer lg:drawer-open">
        <input id="my-drawer-3" type="checkbox" class="drawer-toggle" />
        <div class="drawer-content flex flex-col">
            <!-- Navbar -->
            <div class="navbar bg-gray-50 text-blue-500 shadow-sm">
                <div class="navbar-start">
                    <label for="my-drawer-3" aria-label="open sidebar" class="btn btn-square btn-ghost lg:hidden">
                    <div class="">
                        <div tabindex="0" role="button" class="btn btn-ghost btn-circle">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /> </svg>
                        </div>
                    </div>
                    </label>
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
            <div class="m-4" >
                @yield('contents')

            </div>

        </div>
        @include('backend.layouts.parts._sidebar')

    </div>
    @livewireScripts
    @filamentScripts
    @vite('resources/js/backend.js')
{{--    <div class="navbar bg-greeb-100 shadow-sm mb-6">--}}

{{--        <div class="navbar-start">--}}
{{--            <div class="dropdown">--}}
{{--                <div tabindex="0" role="button" class="btn btn-ghost btn-circle">--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7" /> </svg>--}}
{{--                </div>--}}
{{--                <ul--}}
{{--                    tabindex="0"--}}
{{--                    class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">--}}
{{--                    <li><a>Homepage</a></li>--}}
{{--                    <li><a>Portfolio</a></li>--}}
{{--                    <li><a>About</a></li>--}}
{{--                </ul>--}}
{{--            </div>--}}
{{--        </div>--}}

{{--        <div class="navbar-center">--}}
{{--            <a class="btn btn-ghost text-xl">daisyUI</a>--}}
{{--        </div>--}}
{{--        <div class="navbar-end">--}}

{{--            <button class="btn btn-ghost btn-circle">--}}
{{--                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" /> </svg>--}}
{{--            </button>--}}
{{--            <button class="btn btn-ghost btn-circle">--}}
{{--                <div class="indicator">--}}
{{--                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" /> </svg>--}}
{{--                    <span class="badge badge-xs badge-primary indicator-item"></span>--}}
{{--                </div>--}}
{{--            </button>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--    <div>--}}
{{--        <h3>Hello work</h3>--}}
{{--    </div>--}}
</body>
</html>
