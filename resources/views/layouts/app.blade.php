<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

</head>

<body class="font-sans antialiased bg-gray-100">

    <div x-data="{ sidebarOpen: true }" class="flex min-h-screen">

        <div class="fixed h-full z-30 transition-all duration-300 bg-gray-900"
            :class="sidebarOpen ? 'w-64' : 'w-0 overflow-hidden'">
            <x-sidebar />
        </div>

        <div class="flex-1 flex flex-col transition-all duration-300" :class="sidebarOpen ? 'ml-64' : 'ml-0'">

            <nav class="fixed top-0 right-0 h-16 bg-white border-b border-gray-200 flex items-center justify-between px-6 z-40 shadow-sm transition-all duration-300"
                :class="sidebarOpen ? 'left-64' : 'left-0'">

                <div class="flex items-center gap-4">

                    <button @click="sidebarOpen = !sidebarOpen"
                        class="text-gray-600 text-xl hover:text-indigo-600 transition">
                        ☰
                    </button>
                    @canany(['manager activity'])
                        <a href="{{ route('activity.logs') }}"
                            class="flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-semibold
                                bg-indigo-600 text-white shadow-md shadow-indigo-500/30
                                hover:bg-indigo-700 hover:shadow-lg hover:scale-105
                                transition-all duration-200">

                            <!-- ICON -->
                            <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 17v-6h13M9 5v6h13M5 12h.01" />
                            </svg>

                            <span>Activity Logs</span>
                        </a>
                    @endcanany

                </div>

                <!-- RIGHT -->
                <div class="flex items-center gap-5">

                    <!-- Notifications -->
                    <a href="{{ route('notifications.index') }}"
                        class="relative text-gray-600 hover:text-indigo-600 transition">

                        🔔

                        @if (auth()->user()->unreadNotifications()->count() > 0)
                            <span
                                class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center shadow">
                                {{ auth()->user()->unreadNotifications()->count() }}
                            </span>
                        @endif

                    </a>

                    <!-- USER -->
                    <x-dropdown align="right" width="48">

                        <x-slot name="trigger">
                            <button class="flex items-center gap-2 px-3 py-2 rounded-lg hover:bg-gray-100 transition">

                                <!-- Avatar -->
                                <div
                                    class="w-8 h-8 bg-indigo-600 text-white flex items-center justify-center rounded-full text-sm font-bold">
                                    {{ strtoupper(substr(Auth::user()->first_name, 0, 1)) }}
                                </div>

                                <span class="text-gray-700 text-sm font-medium">
                                    {{ Auth::user()->first_name }}
                                </span>

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
                                    Logout
                                </x-dropdown-link>
                            </form>

                        </x-slot>

                    </x-dropdown>

                </div>

            </nav>

            <!-- ================= CONTENT ================= -->
            <main class="p-6 mt-16">

                <!-- ALERT -->
                @if (session('status'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                        class="mb-4 p-3 bg-green-100 text-green-700 rounded shadow flex justify-between">

                        <span>{{ session('status') }}</span>
                        <button @click="show = false">✖</button>
                    </div>
                @endif

                @if (session('error'))
                    <div x-data="{ show: true }" x-init="setTimeout(() => show = false, 3000)" x-show="show"
                        class="mb-4 p-3 bg-red-100 text-red-700 rounded shadow flex justify-between">

                        <span>{{ session('error') }}</span>
                        <button @click="show = false">✖</button>
                    </div>
                @endif

                @yield('content')

            </main>

        </div>

    </div>

</body>

@stack('scripts')

</html>
