@extends('layouts.app')

@section('content')
    <div class="space-y-6">

        <!-- ================= STATS ================= -->
        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-5 gap-6">

            <div class="bg-gray-800 p-5 rounded-xl">
                <p class="text-gray-400 text-sm">Pending</p>
                <h2 class="text-2xl font-bold text-yellow-400">{{ $pendingTasks }}</h2>
            </div>

            <div class="bg-gray-800 p-5 rounded-xl">
                <p class="text-gray-400 text-sm">In Progress</p>
                <h2 class="text-2xl font-bold text-blue-400">{{ $inProgressTasks }}</h2>
            </div>

            <div class="bg-gray-800 p-5 rounded-xl">
                <p class="text-gray-400 text-sm">Completed</p>
                <h2 class="text-2xl font-bold text-green-400">{{ $completedTasks }}</h2>
            </div>

            <div class="bg-gray-800 p-5 rounded-xl">
                <p class="text-gray-400 text-sm">Today</p>
                <h2 class="text-2xl font-bold text-indigo-400">{{ $todayTasks }}</h2>
            </div>

            <div class="bg-gray-800 p-5 rounded-xl">
                <p class="text-gray-400 text-sm">Progress</p>
                <h2 class="text-2xl font-bold text-green-400">{{ $completionRate }}%</h2>

                <div class="w-full bg-gray-700 h-2 mt-2 rounded-full">
                    <div class="bg-green-500 h-2 rounded-full" style="width: {{ $completionRate }}%">
                    </div>
                </div>
            </div>

        </div>

        <!-- ================= TASK LIST ================= -->
        <div class="bg-gray-800 rounded-xl">

            <div class="p-4 border-b border-gray-700">
                <h2 class="text-white text-lg">My Tasks</h2>
            </div>

            @forelse($myTasks as $task)
                <div class="p-4 border-b border-gray-700 flex justify-between items-center">

                    <!-- LEFT -->
                    <div>
                        <p class="text-white font-medium">{{ $task->title }}</p>

                        <p class="text-sm text-gray-400">
                            {{ $task->project->title ?? 'No Project' }} •
                            {{ $task->created_at->diffForHumans() }}
                        </p>
                    </div>

                    <!-- STATUS BADGE -->
                    <span
                        class="text-xs px-3 py-1 rounded-full
    @if ($task->status->value == 'open') bg-yellow-500
    @elseif($task->status->value == 'in progress') bg-blue-500
    @elseif($task->status->value == 'completed') bg-green-500 @endif
">
                        {{ ucfirst($task->status->value) }}
                    </span>

                </div>
            @empty
                <p class="p-4 text-gray-400">No tasks assigned</p>
            @endforelse

        </div>

    </div>
@endsection
