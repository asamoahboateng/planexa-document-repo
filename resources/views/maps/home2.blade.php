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

<div class="drawer drawer-end">
    <input id="my-drawer-4" type="checkbox" class="drawer-toggle" />
    <div class="drawer-content">
        <!-- Page content here -->
        <label for="my-drawer-4" class="drawer-button btn btn-primary">Open drawer</label>
    </div>
    
    <div class="drawer-side">
        <label for="my-drawer-4" aria-label="close sidebar" class="drawer-overlay"></label>
        <ul class="menu bg-base-200 text-base-content min-h-full w-80 p-4">
            <!-- Sidebar content here -->
            <li><a>Sidebar Item 1</a></li>
            <li><a>Sidebar Item 2</a></li>
        </ul>
    </div>
</div>

<div class="navbar bg-base-100 shadow-sm">
    <div class="navbar-start">
        <a class="btn btn-ghost text-xl">daisyUI</a>
    </div>

    <div class="navbar-end lg:hidden">
        <div class="dropdown">
            <div tabindex="0" role="button" class="btn btn-ghost">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor"> <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h8m-8 6h16" /> </svg>
            </div>
            <ul
                tabindex="0"
                class="menu menu-sm dropdown-content bg-base-100 rounded-box z-1 mt-3 w-52 p-2 shadow">
                <li><a>Item 1</a></li>
                <li>
                    <a>Parent</a>
                    <ul class="p-2">
                        <li><a>Submenu 1</a></li>
                        <li><a>Submenu 2</a></li>
                    </ul>
                </li>
                <li><a>Item 3</a></li>
            </ul>
        </div>
    </div>
    <div class="navbar-end hidden lg:flex">
        <ul class="menu menu-horizontal px-1">
            <li><a>Item 1</a></li>
            <li>
                <details>
                    <summary>Parent</summary>
                    <ul class="p-2">
                        <li><a>Submenu 1</a></li>
                        <li><a>Submenu 2</a></li>
                    </ul>
                </details>
            </li>
            <li><a>Item 3</a></li>
        </ul>
    </div>
{{--    <div class="navbar-end">--}}
{{--        <a class="btn">Button</a>--}}
{{--    </div>--}}
</div>

{{--<div class="navbar bg-gray-50 text-blue-500 shadow-sm">--}}
{{--    <div class="navbar-start">--}}
{{--        <a class="btn btn-ghost xl:text-xl">{{ config('app.name') }}</a>--}}
{{--    </div>--}}
{{--    <div class="navbar-end">--}}
{{--        <ul class="menu menu-horizontal px-1 sm:hidden">--}}
{{--            <li><a>Item 1</a></li>--}}
{{--            <li>--}}
{{--                <details>--}}
{{--                    <summary>Parent</summary>--}}
{{--                    <ul class="p-2">--}}
{{--                        <li><a>Submenu 1</a></li>--}}
{{--                        <li><a>Submenu 2</a></li>--}}
{{--                    </ul>--}}
{{--                </details>--}}
{{--            </li>--}}
{{--            <li><a>Item 3</a></li>--}}
{{--        </ul>--}}

{{--    </div>--}}
{{--</div>--}}
<!-- Page content here -->
@yield('contents')
@livewireScripts
@filamentScripts
@vite('resources/js/backend.js')
</body>
</html>
