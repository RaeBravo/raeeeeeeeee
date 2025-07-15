<nav x-data="{ open: false }" class="bg-white border-b border-gray-100">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-16">
            <!-- Branding -->
            <div class="flex items-center">
                <a href="{{ route('dashboard') }}" class="text-lg font-bold text-gray-800 hover:text-blue-600">
                    Booking System
                </a>
            </div>

            <!-- Right-side menu -->
            <div class="hidden sm:flex sm:items-center sm:space-x-4">
                <!-- Notifications Dropdown -->
                <div class="relative" x-data="{ notifOpen: false }">
                    <button @click="notifOpen = !notifOpen" class="relative focus:outline-none">
                        <svg class="w-6 h-6 text-gray-600 hover:text-blue-600" fill="none" stroke="currentColor"
                             stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                  d="M15 17h5l-1.405-1.405A2.032 2.032 0 
                                  0118 14.158V11a6.002 6.002 0 
                                  00-4-5.659V5a2 2 0 10-4 
                                  0v.341C7.67 6.165 6 8.388 6 
                                  11v3.159c0 .538-.214 1.055-.595 
                                  1.436L4 17h5m6 0v1a3 3 0 
                                  11-6 0v-1m6 0H9" />
                        </svg>
                        @if(auth()->user()->unreadNotifications->count())
                            <span class="absolute top-0 right-0 inline-flex items-center justify-center px-1.5 py-0.5 text-xs font-bold leading-none text-white bg-red-600 rounded-full">
                                {{ auth()->user()->unreadNotifications->count() }}
                            </span>
                        @endif
                    </button>

                    <!-- Dropdown -->
                    <div x-show="notifOpen" @click.away="notifOpen = false"
                         class="absolute right-0 mt-2 w-64 bg-white rounded-md shadow-lg z-50 p-3 text-sm text-gray-700">
                        <p class="font-semibold mb-2">Notifications</p>
                        @forelse(auth()->user()->unreadNotifications->take(5) as $notification)
                            <div class="border-b py-1">
                                {{ $notification->data['message'] ?? 'New notification' }}
                                <div class="text-xs text-gray-400">{{ $notification->created_at->diffForHumans() }}</div>
                            </div>
                        @empty
                            <p class="text-gray-500">No new notifications</p>
                        @endforelse
                        <div class="mt-2 text-right">
                            <a href="{{ route('notifications.index') }}" class="text-blue-600 text-xs hover:underline">
                                View All
                            </a>
                        </div>
                    </div>
                </div>

                <!-- User dropdown -->
                <x-dropdown align="right" width="48">
                    <x-slot name="trigger">
                        <button class="flex items-center text-sm font-medium text-gray-500 hover:text-gray-700">
                            <div>{{ Auth::user()->name }}</div>
                            <div class="ml-1">
                                <svg class="w-4 h-4 fill-current" viewBox="0 0 20 20">
                                    <path d="M5.293 7.293a1 1 0 
                                    011.414 0L10 10.586l3.293-3.293a1 
                                    1 0 011.414 1.414L10 
                                    13.414l-4.707-4.707a1 1 0 
                                    010-1.414z" />
                                </svg>
                            </div>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <x-dropdown-link :href="route('profile.edit')">
                            Profile
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                Log Out
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>
        </div>
    </div>
</nav>
