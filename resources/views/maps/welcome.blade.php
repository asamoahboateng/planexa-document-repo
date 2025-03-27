<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Responsive Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
                <h1 class="text-lg font-semibold">Dashboard</h1>
                <div class="text-blue-900">User</div>
            </header>
            
            <!-- Main Content -->
            <main class="flex-1 p-6">
                <div class="bg-white p-4 rounded shadow">Welcome to your dashboard!</div>
            </main>
        </div>
    </div>
    
    <script>
        function toggleSidebar() {
            const sidebar = document.querySelector('aside');
            sidebar.classList.toggle('hidden');
        }
    </script>
</body>
</html>

