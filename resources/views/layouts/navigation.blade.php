<nav x-data="{ open: false }" class="bg-white border-b border-gray-200 shadow-sm">
    <!-- Conteneur principal -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16 items-center">
            
            <!-- 🔹 Logo -->
            <div class="flex items-center space-x-4">
                <a href="{{ route('dashboard') }}" class="flex items-center space-x-2">
                    <img src="{{ asset('logoBS.png') }}" alt="Logo" class="h-9 w-9 rounded-full">
                    <span class="font-semibold text-lg text-red-700">Banque de Sang</span>
                </a>
            </div>

            <!-- 🔹 Liens de navigation (Desktop) -->
            <div class="hidden sm:flex items-center space-x-6">
                <x-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                    🏠 {{ __('Dashboard') }}
                </x-nav-link>

                @auth
                    <x-nav-link :href="route('bloodbank.create')" :active="request()->routeIs('bloodbank.create')">
                        ➕ {{ __('Nouvelle Demande') }}
                    </x-nav-link>

                    <x-nav-link :href="route('statistics.index')" :active="request()->routeIs('statistics.index')">
                        📊 {{ __('Statistiques') }}
                    </x-nav-link>

                    @if(Auth::user()->role === 'admin')
                        <x-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                            👥 {{ __('Utilisateurs') }}
                        </x-nav-link>
                    @endif
                @endauth
            </div>

            <!-- 🔹 Menu utilisateur (Desktop) -->
            <div class="hidden sm:flex items-center">
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button
                            class="inline-flex items-center px-3 py-2 border border-transparent text-sm font-medium rounded-md text-gray-600 hover:text-gray-900 focus:outline-none">
                            <div>{{ Auth::user()->name ?? 'Invité' }}</div>
                            <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none"
                                viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M19 9l-7 7-7-7" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('user.profile')">👤 {{ __('Mon profil') }}</x-dropdown-link>
                        <x-dropdown-link :href="route('profile.edit')">⚙️ {{ __('Modifier le profil') }}</x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                🚪 {{ __('Déconnexion') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- 🔹 Bouton mobile -->
            <div class="flex sm:hidden">
                <button @click="open = ! open"
                    class="inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:bg-gray-200 focus:outline-none transition">
                    <svg class="h-6 w-6" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open}" class="inline-flex"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open}" class="hidden"
                            stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- 🔹 Menu mobile déroulant -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden bg-gray-50 border-t border-gray-200">
        <div class="pt-3 pb-3 space-y-2">
            <x-responsive-nav-link :href="route('dashboard')" :active="request()->routeIs('dashboard')">
                🏠 {{ __('Dashboard') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('bloodbank.create')" :active="request()->routeIs('bloodbank.create')">
                ➕ {{ __('Nouvelle Demande') }}
            </x-responsive-nav-link>

            <x-responsive-nav-link :href="route('statistics.index')" :active="request()->routeIs('statistics.index')">
                📊 {{ __('Statistiques') }}
            </x-responsive-nav-link>

            @if(Auth::user()->role === 'admin')
                <x-responsive-nav-link :href="route('users.index')" :active="request()->routeIs('users.*')">
                    👥 {{ __('Utilisateurs') }}
                </x-responsive-nav-link>
            @endif
        </div>

        <div class="border-t border-gray-300 pt-3 pb-2">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">{{ Auth::user()->name ?? '' }}</div>
                <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email ?? '' }}</div>
            </div>

            <div class="mt-3 space-y-1">
                <x-responsive-nav-link :href="route('user.profile')">👤 {{ __('Mon profil') }}</x-responsive-nav-link>
                <x-responsive-nav-link :href="route('profile.edit')">⚙️ {{ __('Modifier le profil') }}</x-responsive-nav-link>

                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <x-responsive-nav-link :href="route('logout')"
                        onclick="event.preventDefault(); this.closest('form').submit();">
                        🚪 {{ __('Déconnexion') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
