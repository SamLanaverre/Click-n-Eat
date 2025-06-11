<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'ClicknEat') }}</title>
    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <!-- Scripts -->
    @vite(['resources/css/client.css', 'resources/js/app.js'])
    
    <!-- Styles spécifiques au client pour éviter les conflits CSS -->
    <style>
        /* Réinitialisation pour éviter les conflits avec AdminLTE */
        .client-layout .adminlte-styles,
        .client-layout .bootstrap-styles {
            display: none !important;
        }
        
        /* Assurer que les styles Tailwind prennent le dessus */
        .client-layout .bg-white {
            background-color: #ffffff !important;
        }
    </style>
</head>
<body class="font-sans antialiased bg-gray-100 client-layout">
    <div class="flex min-h-screen" x-data="{ sidebarOpen: true }">
        <!-- Sidebar / Navigation latérale -->
        <div class="client-sidebar bg-white shadow-md" :class="{ 'mobile-hidden': !sidebarOpen }">
            <!-- Logo et titre -->
            <div class="p-4 border-b border-gray-200 flex items-center justify-between">
                <h2 class="text-xl font-bold text-blue-600">ClicknEat</h2>
                <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            
            <!-- Menu de navigation -->
            <nav class="mt-6">
                <div class="px-4 py-2">
                    <a href="{{ route('client.dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('client.dashboard') ? 'bg-blue-600 text-yellow-300' : 'text-gray-700 hover:bg-blue-100' }}">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                            </svg>
                            {{ __('Accueil') }}
                        </div>
                    </a>
                </div>
                <div class="px-4 py-2">
                    <a href="{{ route('client.dashboard') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('client.dashboard') ? 'bg-blue-600 text-yellow-300' : 'text-gray-700 hover:bg-blue-100' }}">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                            </svg>
                            {{ __('Restaurants') }}
                        </div>
                    </a>
                </div>
                <div class="px-4 py-2">
                    <a href="{{ route('client.orders') }}" class="block py-2.5 px-4 rounded transition duration-200 {{ request()->routeIs('client.orders*') ? 'bg-blue-600 text-yellow-300' : 'text-gray-700 hover:bg-blue-100' }}">
                        <div class="flex items-center">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16 11V7a4 4 0 00-8 0v4M5 9h14l1 12H4L5 9z" />
                            </svg>
                            {{ __('Mes Commandes') }}
                        </div>
                    </a>
                </div>
            </nav>
        </div>
        
        <!-- Contenu principal -->
        <div class="client-content" :class="{ 'sidebar-open': sidebarOpen }">
            <!-- Barre de navigation supérieure -->
            <nav class="bg-white shadow-sm">
                <div class="px-4 py-3 flex items-center justify-between">
                    <!-- Bouton hamburger pour mobile -->
                    <button @click="sidebarOpen = !sidebarOpen" class="md:hidden text-gray-500 hover:text-gray-700 focus:outline-none">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                    </button>
                    
                    <!-- Menu utilisateur -->
                    <div class="flex items-center">
                        <!-- Menu utilisateur avec dropdown -->
                        <div class="relative" x-data="{ open: false }">
                            <button @click="open = !open" class="flex items-center text-gray-700 hover:text-gray-900 focus:outline-none">
                                <span class="hidden sm:inline-block mr-2">{{ Auth::user()->name }}</span>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-8 w-8 text-gray-500" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-6-3a2 2 0 11-4 0 2 2 0 014 0zm-2 4a5 5 0 00-4.546 2.916A5.986 5.986 0 0010 16a5.986 5.986 0 004.546-2.084A5 5 0 0010 11z" clip-rule="evenodd" />
                                </svg>
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 ml-1" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 mt-2 w-48 bg-white rounded-md shadow-lg py-1 z-50" style="display: none;">
                                <a href="{{ route('profile.edit') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                    <div class="flex items-center">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                        </svg>
                                        {{ __('Profil') }}
                                    </div>
                                </a>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                        <div class="flex items-center">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                            </svg>
                                            {{ __('Déconnexion') }}
                                        </div>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>

                </div>
            </nav>

            <!-- Header -->
            @if (isset($header))
                <header class="bg-white shadow">
                    <div class="max-w-7xl mx-auto py-4 px-4 sm:px-6 lg:px-8">
                        <h1 class="text-2xl font-semibold text-gray-800">{{ $header }}</h1>
                    </div>
                </header>
            @endif

            <!-- Page Content -->
            <main class="py-6 px-4 sm:px-6 lg:px-8">
                <!-- Affichage des messages de session -->
                @if (session('status'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ session('status') }}
                    </div>
                @endif

                @if (session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4 flex items-center">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        {{ session('error') }}
                    </div>
                @endif

                {{ $slot }}
            </main>

            <!-- Footer -->
            <footer class="bg-white shadow-inner mt-auto py-4">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center text-gray-500 text-sm">
                        &copy; {{ date('Y') }} {{ config('app.name') }}. Tous droits réservés.
                    </div>
                </div>
            </footer>
        </div>
    </div>

    <!-- Scripts Alpine.js pour les interactions -->
    <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
</body>
</html>
