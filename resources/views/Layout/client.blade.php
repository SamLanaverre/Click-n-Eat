<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="font-sans antialiased bg-gray-100">
    <div class="min-h-screen" x-data="{ open: false }">
        <!-- Flowbite Navbar -->
        <nav class="bg-white border-b border-gray-200 dark:bg-gray-900 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-wrap items-center justify-between h-16">
                    <!-- Logo -->
                    <a href="{{ route('dashboard') }}" class="flex items-center gap-2">
                        <x-application-logo class="h-9 w-auto text-primary-600" />
                        <span class="self-center text-xl font-semibold whitespace-nowrap text-primary-700">{{ config('app.name', 'ClicknEat') }}</span>
                    </a>
                    <!-- Burger button -->
                    <button data-collapse-toggle="navbar-client" type="button" class="inline-flex items-center p-2 ml-3 text-sm text-gray-500 rounded-lg md:hidden hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-gray-200 dark:text-gray-400 dark:hover:bg-gray-700 dark:focus:ring-gray-600" aria-controls="navbar-client" aria-expanded="false">
                        <span class="sr-only">Ouvrir le menu principal</span>
                        <svg class="w-6 h-6" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path></svg>
                    </button>
                    <!-- Links & User Dropdown -->
                    <div class="hidden w-full md:flex md:w-auto" id="navbar-client">
                        <ul class="flex flex-col font-medium mt-4 rounded-lg bg-gray-50 md:flex-row md:space-x-8 md:mt-0 md:bg-white dark:bg-gray-800 md:dark:bg-gray-900">
                            <li>
                                <a href="{{ route('dashboard') }}" class="block py-2 pl-3 pr-4 text-gray-900 rounded md:bg-transparent md:p-0 md:text-primary-700 dark:text-white {{ request()->routeIs('dashboard') ? 'md:text-primary-600 font-bold' : '' }}">Accueil</a>
                            </li>
                            <li>
                                <a href="{{ route('restaurants.index') }}" class="block py-2 pl-3 pr-4 text-gray-900 rounded md:bg-transparent md:p-0 md:text-primary-700 dark:text-white {{ request()->routeIs('restaurants.*') ? 'md:text-primary-600 font-bold' : '' }}">Restaurants</a>
                            </li>
                            @auth
                            <li>
                                <a href="{{ route('orders.index') }}" class="block py-2 pl-3 pr-4 text-gray-900 rounded md:bg-transparent md:p-0 md:text-primary-700 dark:text-white {{ request()->routeIs('orders.*') ? 'md:text-primary-600 font-bold' : '' }}">Mes Commandes</a>
                            </li>
                            <li>
                                <!-- Dropdown utilisateur -->
                                <button id="user-menu-button" data-dropdown-toggle="user-dropdown" data-dropdown-placement="bottom-end" type="button" class="flex items-center text-sm rounded-full focus:ring-2 focus:ring-primary-500 dark:focus:ring-primary-600">
                                    <span class="sr-only">Ouvrir menu utilisateur</span>
                                    <img class="w-8 h-8 rounded-full" src="https://ui-avatars.com/api/?name={{ urlencode(Auth::user()->name) }}&background=3B82F6&color=fff" alt="avatar">
                                </button>
                                <div id="user-dropdown" class="z-50 hidden bg-white divide-y divide-gray-100 rounded-lg shadow w-44 dark:bg-gray-700">
                                    <div class="px-4 py-3 text-sm text-gray-900 dark:text-white">
                                        <div>{{ Auth::user()->name }}</div>
                                        <div class="font-medium truncate">{{ Auth::user()->email }}</div>
                                    </div>
                                    <ul class="py-1 text-sm text-gray-700 dark:text-gray-200" aria-labelledby="user-menu-button">
                                        <li>
                                            <a href="{{ route('profile.edit') }}" class="block px-4 py-2 hover:bg-gray-100 dark:hover:bg-gray-600 dark:hover:text-white">Profil</a>
                                        </li>
                                    </ul>
                                    <div class="py-1">
                                        <form method="POST" action="{{ route('logout') }}">
                                            @csrf
                                            <button type="submit" class="block w-full px-4 py-2 text-left text-sm text-red-600 hover:bg-gray-100 dark:hover:bg-gray-600 dark:text-red-400">Se déconnecter</button>
                                        </form>
                                    </div>
                                </div>
                            </li>
                            @endauth
                            @guest
                            <li>
                                <a href="{{ route('login') }}" class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-primary-700 md:p-0 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">Se connecter</a>
                            </li>
                            <li>
                                <a href="{{ route('register') }}" class="block py-2 pl-3 pr-4 text-gray-700 rounded hover:bg-gray-100 md:hover:bg-transparent md:hover:text-primary-700 md:p-0 dark:text-gray-400 dark:hover:bg-gray-700 dark:hover:text-white md:dark:hover:bg-transparent">S'inscrire</a>
                            </li>
                            @endguest
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
        <!-- FIN NAVBAR FLOWBITE -->

        <!-- Page Heading -->
        @if (isset($header))
            <header class="bg-white shadow">
                <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endif

        <!-- Page Content -->
        <main>
            <!-- Flash Messages -->
            @if (session('status'))
                <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative" role="alert">
                        {{ session('status') }}
                    </div>
                </div>
            @endif

            @if (session('error'))
                <div class="max-w-7xl mx-auto mt-4 px-4 sm:px-6 lg:px-8">
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative" role="alert">
                        {{ session('error') }}
                    </div>
                </div>
            @endif

            <!-- Content -->
            <div class="py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    {{ $slot }}
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-white border-t border-gray-200">
            <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
                <div class="text-center text-sm text-gray-500">
                    &copy; {{ date('Y') }} {{ config('app.name', 'Laravel') }}. Tous droits réservés.
                </div>
            </div>
        </footer>
    </div>
</body>
</html>
