@extends('layouts.app')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700">

        <!-- Header -->
        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 p-5 border-b dark:border-gray-700">
            <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                Users
            </h2>

            <x-button type="primary" tag="a" href="{{ route('users.create') }}">
                + Add User
            </x-button>
        </div>
        <div class="p-4 border-b dark:border-gray-700 flex justify-between items-center">

            <!-- LEFT SIDE (BUTTON) -->
            <div>
                @if ($withDeleted)
                    <x-button type="primary" tag="a" class="w-auto" href="{{ route('users.index') }}">
                        Show All Users
                    </x-button>
                @else
                    <x-button type="primary" tag="a" class="w-auto"
                        href="{{ route('users.index', ['deleted' => 'true']) }}">
                        Show Deleted Users
                    </x-button>
                @endif
            </div>

            <!-- RIGHT SIDE (SEARCH) -->
            <div>
                <input type="text" id="search" placeholder="Search users..."
                    class="w-full md:w-64 px-3 py-2 border rounded-lg dark:bg-gray-700 dark:text-white">
            </div>

        </div>


        <!-- Table -->
        <div class="overflow-x-auto">
            <table class="w-full table-fixed border-collapse">

                <!-- Head -->
                <thead class="bg-gray-50 dark:bg-gray-700/50 text-xs uppercase text-gray-500 dark:text-gray-400">
                    <tr>
                        <th class="px-4 py-3 text-left">Name</th>
                        <th class="px-4 py-3 text-left">Email</th>
                        <th class="px-4 py-3 text-left">Role</th>
                        <th class="px-4 py-3 text-left">Address</th>
                        <th class="px-4 py-3 text-left">Phone</th>
                        <th class="px-4 py-3 text-left w-[140px]">Terms</th>
                        <th class="px-4 py-3 text-right w-[120px]">Actions</th>
                    </tr>
                </thead>

                <!-- Body -->
                <tbody id="userTable" class="divide-y dark:divide-gray-700 text-sm text-gray-900 dark:text-gray-100">
                    @include('users.table')
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div id="pagination">
            {{ $users->links() }}
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        let timer;

        $('#search').on('keyup', function() {

            clearTimeout(timer); // 🔥 debounce reset

            let query = $(this).val();

            timer = setTimeout(function() {

                $.ajax({
                    url: "{{ route('users.index') }}",
                    type: "GET",
                    data: {
                        search: query
                    },

                    success: function(response) {
                        $('#userTable').html(response);
                    }
                });

            }, 300); // ⏳ debounce delay

        });

        $(document).on('click', '.deleteBtn', function(e) {

            e.preventDefault();

            if (!confirm('Are you sure you want to delete this user?')) return;

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
                    alert('Delete failed ');
                }
            });

        });


        $(document).on('click', '.forceDeleteBtn', function(e) {

            e.preventDefault();

            if (!confirm('Permanently delete this user? ')) return;

            let form = $(this).closest('form');
            let row = $(this).closest('tr');

            $.ajax({
                url: form.attr('action'),
                type: 'POST',
                data: form.serialize(), // includes _method=DELETE

                success: function(response) {

                    row.fadeOut(300, function() {
                        $(this).remove();
                    });
                    showMessage(response.message, 'error');


                },

                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert('Permanent delete failed ');
                }
            });

        });

        $(document).on('click', '.restoreBtn', function(e) {

            e.preventDefault();

            if (!confirm('Restore this user?')) return;

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

                    showMessage(response.message, 'success');


                },

                error: function(xhr) {
                    console.log(xhr.responseText);
                    alert('Restore failed ❌');
                }
            });

        });

        function showMessage(message, type = 'success') {

            let box = $('#ajaxMessage');

            // color map (short trick 🔥)
            const colors = {
                success: 'bg-green-100 text-green-700',
                error: 'bg-red-100 text-red-700',
                warning: 'bg-yellow-100 text-yellow-700'
            };

            // reset + apply color
            box.removeClass().addClass(`mb-4 p-3 rounded ${colors[type] || colors.success}`);

            box.text(message).fadeIn();

            setTimeout(() => box.fadeOut(), 3000);
        }
    </script>
@endpush
