@forelse ($tasks as $task)
    <tr class="hover:bg-gray-50 dark:hover:bg-gray-700/40 transition">

        <!-- Title -->
        <td class="px-4 py-3 font-medium text-indigo-600 dark:text-indigo-400">
            <a href="{{ route('tasks.show', $task->id) }}" class="hover:underline">
                {{ $task->title }}
            </a>
        </td>

        <!-- Description -->
        <td class="px-4 py-3 max-w-[220px] truncate" title="{{ $task->description }}">
            {{ $task->description }}
        </td>

        <!-- ✅ CLIENT SAFE -->
        <td class="px-4 py-3 max-w-[180px] truncate" title="{{ optional($task->client)->company_name }}">
            {{ optional($task->client)->company_name ?? '-' }}
        </td>

        <!-- ✅ PROJECT SAFE -->
        <td class="px-4 py-3 max-w-[180px] truncate" title="{{ optional($task->project)->title }}">
            {{ optional($task->project)->title ?? '-' }}
        </td>

        <!-- ✅ USER SAFE -->
        <td class="px-4 py-3">
            {{ optional($task->user)->full_name ?? 'No user' }}
        </td>

        <!-- ✅ STATUS SAFE -->
        <td class="px-4 py-3">
            @php
                $status = optional($task->status)->value;
            @endphp

            <span
                class="px-2 py-1 text-xs font-medium rounded-full
                {{ $status === 'open' ? 'bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300' : '' }}
                {{ $status === 'in progress' ? 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300' : '' }}
                {{ $status === 'completed' ? 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-300' : '' }}
                {{ $status === 'cancelled' ? 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-300' : '' }}
                {{ $status === 'closed' ? 'bg-purple-100 text-purple-800 dark:bg-purple-900 dark:text-purple-300' : '' }}
            ">
                {{ optional($task->status)->label() ?? '-' }}
            </span>
        </td>

        <!-- ✅ DEADLINE SAFE -->
        <td class="px-4 py-3">
            {{ $task->deadline ? $task->deadline->format('Y-m-d') : '-' }}
        </td>

        <!-- Actions -->
        <td class="px-4 py-3 text-right">
            <div class="flex justify-end space-x-2">

                <!-- Edit -->
                <a href="{{ route('tasks.edit', $task->id) }}"
                    class="text-indigo-600 hover:text-indigo-900 dark:text-indigo-400 dark:hover:text-indigo-300">

                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                        stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                    </svg>

                </a>

                <!-- Delete -->
                @can('delete')
                    <form class="deleteForm" action="{{ route('tasks.destroy', $task->id) }}" method="POST">
                        @csrf
                        @method('DELETE')

                        <button type="button" class="deleteBtn text-red-600 hover:text-red-900">

                             <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24"
                                    stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                </svg>

                        </button>
                    </form>
                @endcan

            </div>
        </td>

    </tr>
@empty
    <tr>
        <td colspan="8" class="text-center py-6 text-gray-500 dark:text-gray-400">
            No tasks found
        </td>
    </tr>
@endforelse
