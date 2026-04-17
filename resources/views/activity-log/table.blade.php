<div class="overflow-x-auto">
    <table class="w-full table-auto border-collapse">

        <!-- Head -->
        <thead class="bg-gray-50 dark:bg-gray-700 text-xs uppercase text-gray-500 dark:text-gray-400">
            <tr>
                <th class="px-4 py-3 text-left">User</th>
                <th class="px-4 py-3 text-left">Action</th>
                <th class="px-4 py-3 text-left">Model</th>
                <th class="px-4 py-3 text-left">Changes</th>
                <th class="px-4 py-3 text-left">Date</th>
            </tr>
        </thead>

        <!-- Body -->
        <tbody class="divide-y dark:divide-gray-700 text-sm text-gray-900 dark:text-gray-100">
            @forelse ($logs as $log)
                <tr>
                    <td class="px-4 py-3">
                        {{ $log->causer?->full_name ?? 'System' }}
                    </td>

                    <td class="px-4 py-3">
                        <span class="px-2 py-1 rounded text-xs
                            @if($log->event == 'created') bg-green-500
                            @elseif($log->event == 'updated') bg-blue-500
                            @elseif($log->event == 'deleted') bg-red-500
                            @endif
                        ">
                            {{ ucfirst($log->event) }}
                        </span>
                    </td>

                    <td class="px-4 py-3">
                        {{ class_basename($log->subject_type) }}
                    </td>

                    <td class="px-4 py-3 text-xs">
                        @if ($log->properties && isset($log->properties['attributes']))
                            @php
                                $attributes = $log->properties['attributes'] ?? [];
                                $old = $log->properties['old'] ?? [];
                            @endphp

                            <div class="space-y-1">
                                @foreach ($attributes as $key => $value)
                                    <div>
                                        <span class="font-semibold">
                                            {{ ucfirst(str_replace('_', ' ', $key)) }}:
                                        </span>

                                        @if(isset($old[$key]))
                                            <span class="line-through text-red-500">
                                                {{ $old[$key] }}
                                            </span>
                                            →
                                        @endif

                                        <span class="text-green-600">
                                            {{ is_array($value) ? json_encode($value) : $value }}
                                        </span>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            -
                        @endif
                    </td>

                    <td class="px-4 py-3 text-gray-400 text-sm">
                        {{ $log->created_at->diffForHumans() }}
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center py-6 text-gray-400">
                        No activity logs found
                    </td>
                </tr>
            @endforelse
        </tbody>

    </table>
</div>

<!-- Pagination -->
<div class="p-4">
    {{ $logs->links() }}
</div>
