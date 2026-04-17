@extends('layouts.app')

@section('content')

<div class="space-y-6">

    <!-- ================= STATS ================= -->
    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-6">

        <div class="bg-gray-800 p-5 rounded-xl border border-gray-700">
            <p class="text-gray-400 text-sm">Users</p>
            <h2 class="text-3xl font-bold text-white">{{ $totalUsers }}</h2>
        </div>

        <div class="bg-gray-800 p-5 rounded-xl border border-gray-700">
            <p class="text-gray-400 text-sm">Clients</p>
            <h2 class="text-3xl font-bold text-white">{{ $totalClients }}</h2>
        </div>

        <div class="bg-gray-800 p-5 rounded-xl border border-gray-700">
            <p class="text-gray-400 text-sm">Projects</p>
            <h2 class="text-3xl font-bold text-white">{{ $activeProjects }}</h2>
        </div>

        <div class="bg-gray-800 p-5 rounded-xl border border-gray-700">
            <p class="text-gray-400 text-sm">Pending</p>
            <h2 class="text-3xl font-bold text-yellow-400">{{ $pendingTasks }}</h2>
        </div>

        <div class="bg-gray-800 p-5 rounded-xl border border-gray-700">
            <p class="text-gray-400 text-sm">Completion Rate</p>

            <h2 class="text-3xl font-bold text-green-400">
                {{ $completionRate }}%
            </h2>

            <div class="w-full bg-gray-700 rounded-full h-2 mt-3">
                <div class="bg-green-500 h-2 rounded-full"
                     style="width: {{ $completionRate }}%">
                </div>
            </div>
        </div>

    </div>

    <!-- ================= CHARTS ================= -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

        <div class="bg-gray-800 p-5 rounded-xl border border-gray-700">
            <h2 class="text-white mb-3">Task Status</h2>
            <canvas id="taskChart"></canvas>
        </div>

        <div class="bg-gray-800 p-5 rounded-xl border border-gray-700">
            <h2 class="text-white mb-3">Projects</h2>
            <canvas id="projectChart"></canvas>
        </div>

        <div class="bg-gray-800 p-5 rounded-xl border border-gray-700">
            <h2 class="text-white mb-3">Monthly Tasks</h2>
            <canvas id="monthlyChart"></canvas>
        </div>

    </div>

    <!-- ================= LOWER GRID ================= -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- TOP USERS (ONLY ADMIN) -->
        @if(auth()->user()->hasRole('admin'))
        <div class="bg-gray-800 rounded-xl border border-gray-700">
            <div class="p-4 border-b border-gray-700">
                <h2 class="text-white">Top Performers</h2>
            </div>

            @forelse ($topUsers as $user)
                <div class="p-4 flex justify-between text-white border-b border-gray-700">
                    <span>{{ $user->full_name }}</span>
                    <span class="text-indigo-400">{{ $user->completed_tasks }} tasks</span>
                </div>
            @empty
                <p class="p-4 text-gray-400">No data</p>
            @endforelse
        </div>
        @endif

        <!-- ACTIVITY -->
        <div class="bg-gray-800 rounded-xl border border-gray-700">
            <div class="p-4 border-b border-gray-700">
                <h2 class="text-white">Recent Activity</h2>
            </div>

            @forelse ($tasks as $task)
                <div class="p-4 border-b border-gray-700">
                    <p class="text-white font-medium">{{ $task->title }}</p>

                    <p class="text-gray-400 text-sm">
                        {{ $task->project->title ?? 'No Project' }}
                        • {{ $task->created_at->diffForHumans() }}
                    </p>
                </div>
            @empty
                <p class="p-4 text-gray-400">No activity</p>
            @endforelse
        </div>

    </div>

</div>

@endsection


@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

<script>

// TASK CHART
new Chart(document.getElementById('taskChart'), {
    type: 'doughnut',
    data: {
        labels: ['Open','Progress','Done'],
        datasets: [{
            data: @json($taskStats)
        }]
    }
});

// PROJECT CHART
new Chart(document.getElementById('projectChart'), {
    type: 'bar',
    data: {
        labels: ['Open','Closed','Cancelled'],
        datasets: [{
            data: @json($projectStats)
        }]
    }
});

// MONTHLY CHART
new Chart(document.getElementById('monthlyChart'), {
    type: 'line',
    data: {
        labels: ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'],
        datasets: [{
            label: 'Tasks',
            data: @json($monthlyTasks)
        }]
    }
});

</script>
@endpush
