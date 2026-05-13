<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'CHENG KELO FRESH FISH')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        .alert-success { @apply bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative; }
        .alert-error { @apply bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative; }
        .alert-info { @apply bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative; }
        
        /* Mobile responsiveness improvements */
        @media (max-width: 640px) {
            .container { padding-left: 1rem; padding-right: 1rem; }
            .text-xl { font-size: 1.25rem; }
            .text-2xl { font-size: 1.5rem; }
            .text-3xl { font-size: 1.875rem; }
        }
    </style>
</head>
<body class="bg-gray-100">
    @auth
        <nav class="bg-blue-600 text-white shadow-lg">
            <div class="container mx-auto px-4">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('dashboard') }}" class="text-xl font-bold">CHENG KELO FRESH FISH</a>
                    </div>
                    
                    <div class="flex items-center space-x-4">
                        <span class="text-sm hidden sm:inline">Welcome, {{ auth()->user()->name }} ({{ auth()->user()->role }})</span>
                        <a href="{{ route('profile.index') }}" class="hover:bg-blue-700 px-2 py-2 rounded flex items-center">
                            @if(auth()->user()->profile_picture)
                                <img src="{{ asset('uploads/profiles/' . auth()->user()->profile_picture) }}" 
                                     alt="Profile Picture" 
                                     class="h-8 w-8 rounded-full object-cover border-2 border-white">
                            @else
                                <div class="h-8 w-8 rounded-full bg-white flex items-center justify-center border-2 border-white">
                                    <span class="text-blue-600 font-bold text-sm">{{ substr(auth()->user()->name, 0, 1) }}</span>
                                </div>
                            @endif
                        </a>
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="hover:bg-blue-700 px-3 py-2 rounded text-sm">Logout</button>
                        </form>
                    </div>
                </div>
                
                @auth
                    <div class="bg-blue-700">
                        <div class="container mx-auto px-4">
                            <div class="flex flex-wrap gap-2 py-2 text-sm overflow-x-auto">
                                <a href="{{ route('dashboard') }}" class="hover:bg-blue-800 px-3 py-1 rounded whitespace-nowrap">Dashboard</a>
                                
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('users.index') }}" class="hover:bg-blue-800 px-3 py-1 rounded whitespace-nowrap">Users</a>
                                    <a href="{{ route('fish-categories.index') }}" class="hover:bg-blue-800 px-3 py-1 rounded whitespace-nowrap">Categories</a>
                                @endif
                                
                                <a href="{{ route('purchases.index') }}" class="hover:bg-blue-800 px-3 py-1 rounded whitespace-nowrap">Purchases</a>
                                <a href="{{ route('stocks.index') }}" class="hover:bg-blue-800 px-3 py-1 rounded whitespace-nowrap">Stocks</a>
                                <a href="{{ route('sales.index') }}" class="hover:bg-blue-800 px-3 py-1 rounded whitespace-nowrap">Sales</a>
                                
                                <a href="{{ route('catalog.index') }}" class="hover:bg-blue-800 px-3 py-1 rounded whitespace-nowrap">Catalog</a>
                                <a href="{{ route('reports.index') }}" class="hover:bg-blue-800 px-3 py-1 rounded whitespace-nowrap">Reports</a>
                                @if(auth()->user()->isAdmin())
                                    <a href="{{ route('settings.index') }}" class="hover:bg-blue-800 px-3 py-1 rounded whitespace-nowrap">Settings</a>
                                @endif
                            </div>
                        </div>
                    </div>
                @endauth
            </div>
        </nav>
    @endauth

    <main class="container mx-auto px-4 py-4 sm:py-8">
        @auth
            @if(session('success'))
                <div class="alert-success mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="alert-error mb-4">
                    {{ session('error') }}
                </div>
            @endif
        @endauth

        @yield('content')
    </main>

    <footer class="bg-gray-800 text-white py-4 mt-8">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} CHENG KELO FRESH FISH. All rights reserved.</p>
        </div>
    </footer>
</body>
</html>
