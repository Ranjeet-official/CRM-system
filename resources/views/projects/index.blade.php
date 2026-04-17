@extends('layouts.app')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-5 border-b dark:border-gray-700">

            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                Projects
            </h2>

            <x-button type="primary" tag="a" href="{{ route('projects.create') }}">
                + Add Project
            </x-button>

        </div>

        <!-- SEARCH BAR (RIGHT SIDE) -->
        <div class="p-4 border-b dark:border-gray-700 flex justify-end">

            <input type="text" id="search" placeholder="Search clients..."
                class="w-full md:w-64 px-3 py-2 border rounded-lg dark:bg-gray-700 dark:text-white">

        </div>

        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full table-fixed border-collapse">

                <!-- Head -->
                <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3 text-left">Title</th>
                        <th class="px-4 py-3 text-left">Description</th>
                        <th class="px-4 py-3 text-left">Client</th>
                        <th class="px-4 py-3 text-left">User</th>
                        <th class="px-4 py-3 text-left w-[120px]">Status</th>
                        <th class="px-4 py-3 text-left w-[140px]">Deadline</th>
                        <th class="px-4 py-3 text-right w-[120px]">Actions</th>
                    </tr>
                </thead>

                <!-- Body -->
                <tbody id="projectTable" class="divide-y dark:divide-gray-700 text-sm text-gray-900 dark:text-gray-100">
                    @include('projects.table')
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="p-5 border-t dark:border-gray-700">
            {{ $projects->links() }}
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        let timer;

        $('#search').on('keyup', function() {

            clearTimeout(timer);

            let query = $(this).val();

            timer = setTimeout(function() {

                $.ajax({
                    url: "{{ route('projects.index') }}",
                    type: "GET",
                    data: {
                        search: query
                    },

                    success: function(response) {
                        $('#projectTable').html(response);
                    }
                });

            }, 300); // ⏳ debounce delay

        });

        $(document).on('click', '.deleteBtn', function(e) {

            e.preventDefault();

            if (!confirm('Are you sure you want to delete this?')) return;

            let form = $(this).closest('form');
            let row = $(this).closest('tr');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(),

                success: function(response) {

                    row.fadeOut(300, function() {
                        $(this).remove();
                    });

                    showMessage(response.message, 'warning');
                },

                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert('Delete failed ❌');
                }
            });

        });
    </script>
@endpush
