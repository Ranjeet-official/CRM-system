@extends('layouts.app')

@section('content')
    <div class="bg-white dark:bg-gray-800 rounded-xl shadow border border-gray-200 dark:border-gray-700">

        <!-- Header -->
        <div class="p-5 border-b dark:border-gray-700">

            <!-- Title -->
            <div class="flex justify-between items-center mb-5">
                <h2 class="text-lg font-semibold text-gray-800 dark:text-gray-100">
                    Activity Logs
                </h2>
            </div>

            <!-- FILTER CARD -->
            <div class="bg-gray-50 dark:bg-gray-700/40 p-4 rounded-lg">

                <form id="filterForm" class="flex flex-wrap items-end gap-4">

                    <!-- User -->
                    <div class="flex flex-col min-w-[180px]">
                        <label class="text-xs text-gray-500 mb-1">User</label>
                        <select name="user"
                            class="px-3 py-2 border rounded-lg text-sm
                               focus:ring-2 focus:ring-blue-500
                               dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                            <option value="">All Users</option>
                            @foreach ($users as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <!-- Action -->
                    <div class="flex flex-col min-w-[160px]">
                        <label class="text-xs text-gray-500 mb-1">Action</label>
                        <select name="event"
                            class="px-3 py-2 border rounded-lg text-sm
                               focus:ring-2 focus:ring-blue-500
                               dark:bg-gray-800 dark:border-gray-600 dark:text-white">
                            <option value="">All Actions</option>
                            <option value="created">Created</option>
                            <option value="updated">Updated</option>
                            <option value="deleted">Deleted</option>
                        </select>
                    </div>

                    <!-- Buttons -->
                    <div class="flex gap-2 ml-auto">

                        <button type="button" id="resetBtn"
                            class="px-4 py-2 bg-gray-200 text-gray-700 rounded-lg text-sm
                               hover:bg-gray-300 transition">
                            Reset
                        </button>

                        <button type="button" id="filterBtn"
                            class="px-4 py-2 bg-blue-600 text-white rounded-lg text-sm
                               hover:bg-blue-700 transition shadow">
                            Apply
                        </button>

                    </div>

                </form>
            </div>

        </div>

        <!-- TABLE -->
        <div id="tableData" class="p-4">
            @include('activity-log.table')
        </div>

    </div>
@endsection
@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

    <script>
        $(document).ready(function() {

            function fetchData(page = 1) {
                let formData = $('#filterForm').serialize();

                $.ajax({
                    url: "{{ route('activity.logs') }}?page=" + page,
                    type: "GET",
                    data: formData,
                    success: function(data) {
                        $('#tableData').html(data);
                    }
                });
            }

            // Filter button
            $('#filterBtn').on('click', function() {
                fetchData();
            });

            // Reset button
            $('#resetBtn').on('click', function() {
                $('#filterForm')[0].reset();
                fetchData();
            });

            // Pagination AJAX
            $(document).on('click', '.pagination a', function(e) {
                e.preventDefault();
                let page = $(this).attr('href').split('page=')[1];
                fetchData(page);
            });

        });
    </script>
@endpush
