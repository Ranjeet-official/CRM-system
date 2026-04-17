@forelse ($users as $user)
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">

        <!-- Name -->
        <td class="px-4 py-3 font-medium text-gray-900 dark:text-gray-100">
            {{ $user->full_name }}
        </td>

        <!-- Email -->
        <td class="px-4 py-3 max-w-[200px] truncate" title="{{ $user->email }}">
            {{ $user->email }}
        </td>

        <!-- Role -->
        <td class="px-4 py-3 max-w-[160px] truncate" title="{{ $user->roles->pluck('name')->implode(', ') }}">
            {{ $user->roles->pluck('name')->implode(', ') }}
        </td>

        <!-- Address -->
        <td class="px-4 py-3 max-w-[200px] truncate" title="{{ $user->address }}">
            {{ $user->address }}
        </td>

        <!-- Phone -->
        <td class="px-4 py-3">jhg
            {{-- {{ $user->phone_number }} --}}
        </td>

        <!-- Terms -->
        <td class="px-4 py-3">
            <span class="{{ $user->terms_accepted_at ? 'text-green-600' : 'text-red-600' }}">
                {{ $user->terms_accepted_at ? 'Yes' : 'No' }}
            </span>
        </td>

        <!-- Actions -->
        <td class="px-4 py-3 text-right">
            <div class="flex justify-end space-x-2">

                {{-- NORMAL USERS --}}
                @if (!$withDeleted)
                    <!-- Edit -->
                    <a href="{{ route('users.edit', $user->id) }}"
                        class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">

                        <!-- SVG Edit -->
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                            stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                        </svg>
                    </a>

                    <!-- Soft Delete -->
                    @can('delete')
                        <form class="deleteForm" action="{{ route('users.destroy', $user->id) }}" method="POST">
                            @csrf
                            @method('DELETE')

                            <button type="button" class="deleteBtn text-red-600 hover:text-red-900 dark:text-red-400">

                                <!-- SVG Delete -->
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>

                            </button>
                        </form>
                    @endcan

                    {{-- SOFT DELETED USERS --}}
                @else
                    <form class="restoreForm" action="{{ route('users.restore', $user->id) }}" method="POST">
                        @csrf

                        <button type="button"
                            class="restoreBtn text-green-600 hover:text-green-900 dark:text-green-400">

                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 5l-7 7 7 7M2 12h18" />
                            </svg>

                        </button>
                    </form>

                    <!-- Permanent Delete -->
                    <form class="forceDeleteForm" action="{{ route('users.forceDelete', $user->id) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="button" class="forceDeleteBtn text-red-700 hover:text-red-900 dark:text-red-500">

                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>

                        </button>
                    </form>
                @endif

            </div>
        </td>
    </tr>
@empty
    <tr>
        <td colspan="7" class="text-center py-6 text-gray-500 dark:text-gray-400">
            No users found
        </td>
    </tr>
@endforelse


