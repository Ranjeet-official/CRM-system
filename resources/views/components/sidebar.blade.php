<div class="bg-gray-800 text-gray-300 h-full w-64 border-r border-gray-800">

    <!-- Logo -->
    <div class="p-6 font-bold text-xl border-b border-gray-800 text-white">
        CRM System
    </div>

    <nav class="mt-4 space-y-1">

        <!-- Dashboard -->
        <a href="{{ route('dashboard') }}"
           class="flex items-center gap-3 py-2.5 px-5 mx-2 rounded-lg transition
           {{ request()->routeIs('dashboard')
                ? 'bg-indigo-600 text-white shadow'
                : 'hover:bg-gray-800 hover:text-white' }}">

            <!-- SVG -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m-4 0h8" />
            </svg>

            Dashboard
        </a>

        @can('manage users')
        <!-- Users -->
        <a href="{{ route('users.index') }}"
           class="flex items-center gap-3 py-2.5 px-5 mx-2 rounded-lg transition
           {{ request()->routeIs('users.*')
                ? 'bg-indigo-600 text-white shadow'
                : 'hover:bg-gray-800 hover:text-white' }}">

            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5V4H2v16h5m10 0v-4a4 4 0 00-8 0v4m8 0H9" />
            </svg>

            Users
        </a>
        @endcan

        <!-- Clients -->
        <a href="{{ route('clients.index') }}"
           class="flex items-center gap-3 py-2.5 px-5 mx-2 rounded-lg transition
           {{ request()->routeIs('clients.*')
                ? 'bg-indigo-600 text-white shadow'
                : 'hover:bg-gray-800 hover:text-white' }}">

            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7h18M3 12h18M3 17h18" />
            </svg>

            Clients
        </a>

        <!-- Projects -->
        <a href="{{ route('projects.index') }}"
           class="flex items-center gap-3 py-2.5 px-5 mx-2 rounded-lg transition
           {{ request()->routeIs('projects.*')
                ? 'bg-indigo-600 text-white shadow'
                : 'hover:bg-gray-800 hover:text-white' }}">

            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M3 7h5l2 3h11v10H3V7z" />
            </svg>

            Projects
        </a>

        <!-- Tasks -->
        <a href="{{ route('tasks.index') }}"
           class="flex items-center gap-3 py-2.5 px-5 mx-2 rounded-lg transition
           {{ request()->routeIs('tasks.*')
                ? 'bg-indigo-600 text-white shadow'
                : 'hover:bg-gray-800 hover:text-white' }}">

            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2l4-4m5 2a9 9 0 11-18 0a9 9 0 0118 0z" />
            </svg>

            Tasks
        </a>

    </nav>
</div>
