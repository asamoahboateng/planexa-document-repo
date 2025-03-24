<div class="drawer-side bg-base-200">
    <label for="my-drawer-3" aria-label="close sidebar" class="drawer-overlay"></label>
{{--    <ul class="menu bg-gray-200 min-h-full w-80 p-4">--}}
{{--        <!-- Sidebar content here -->--}}
{{--        <li><a>Sidebar Item 1</a></li>--}}
{{--        <li><a>Sidebar Item 2</a></li>--}}
{{--    </ul>--}}

{{--    <div class="divider">home</div>--}}
    <ul class="menu bg-base-200 text-base-content min-h-full w-80 p-4">
        <li class="text-center mx-auto">
            <a class="text-xl text-primary text-center"> <h3 class="text-center">Planexa</h3></a>
        </li>
        <li class="my-4">
            <a class="text-lg"> @svg('heroicon-o-home', 'w-6 h-6') Home</a>
        </li>
        <li>
            <details >
                <summary>Parent</summary>
                <ul>
                    <li><a>Submenu 1</a></li>
                    <li><a>Submenu 2</a></li>
                    <li>
                        <details>
                            <summary>Parent</summary>
                            <ul>
                                <li><a>Submenu 1</a></li>
                                <li><a>Submenu 2</a></li>
                            </ul>
                        </details>
                    </li>
                </ul>
            </details>
        </li>
        <li><a>Item 3</a></li>
    </ul>
</div>
