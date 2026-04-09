<aside class="w-64 bg-gray-800 text-white fixed h-full z-20">
    <!-- Sidebar content -->
    <div class="p-6 font-bold text-lg border-b border-gray-700">
        CRM System
    </div>
    <nav class="mt-4">
        <a href="{{ route('dashboard') }}" class="block py-2 px-6 hover:bg-gray-700">Dashboard</a>
       @can('manage users')
       <a href="{{ route('users.index') }}" class="block py-2 px-6 hover:bg-gray-700">Users</a>
       @endcan
        <a href="{{ route('clients.index') }}" class="block py-2 px-6 hover:bg-gray-700">Clients</a>
        <a href="{{ route('tasks.index') }}" class="block py-2 px-6 hover:bg-gray-700">Tasks</a>
        <a href="{{ route('projects.index') }}" class="block py-2 px-6 hover:bg-gray-700">Projects</a>
    </nav>
</aside>


