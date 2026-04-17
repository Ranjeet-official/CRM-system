<?php

namespace App\Http\Controllers;

use App\Models\Task;
use App\Models\User;
use App\Models\Client;
use App\Models\Project;
use App\Enums\TaskStatus;
use App\Enums\ProjectStatus;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        // ================= ADMIN DASHBOARD =================
        if ($user->hasRole('admin')) {

            $totalTasks = Task::count();
            $completedTasks = Task::where('status', TaskStatus::COMPLETED)->count();

            $completionRate = $totalTasks > 0
                ? round(($completedTasks / $totalTasks) * 100)
                : 0;

            return view('dashboard.admin', [

                // ===== STATS =====
                'totalUsers' => User::count(),
                'totalClients' => Client::count(),
                'totalProjects' => Project::count(),
                'activeProjects' => Project::where('status', ProjectStatus::OPEN)->count(),
                'pendingTasks' => Task::where('status', TaskStatus::OPEN)->count(),

                'completionRate' => $completionRate,

                'tasks' => Task::latest()
                    ->take(5)
                    ->with(['project', 'user'])
                    ->get(),

                // ===== TOP USERS =====
                'topUsers' => User::withCount(['tasks as completed_tasks' => function ($q) {
                    $q->where('status', TaskStatus::COMPLETED);
                }])->orderByDesc('completed_tasks')
                  ->take(5)
                  ->get(),

                // ===== CHARTS =====
                'taskStats' => [
                    Task::where('status', TaskStatus::OPEN)->count(),
                    Task::where('status', TaskStatus::IN_PROGRESS)->count(),
                    Task::where('status', TaskStatus::COMPLETED)->count(),
                ],

                'projectStats' => [
                    Project::where('status', ProjectStatus::OPEN)->count(),
                    Project::where('status', ProjectStatus::CLOSED)->count(),
                    Project::where('status', ProjectStatus::CANCELLED)->count(),
                ],

                // ===== MONTHLY TASKS =====
                'monthlyTasks' => collect(range(1, 12))->map(function ($month) {
                    return Task::whereMonth('created_at', $month)->count();
                }),

            ]);
        }

        // ================= USER DASHBOARD =================

        $totalTasks = Task::where('user_id', $user->id)->count();

        $completedTasks = Task::where('user_id', $user->id)
            ->where('status', TaskStatus::COMPLETED)
            ->count();

        $completionRate = $totalTasks > 0
            ? round(($completedTasks / $totalTasks) * 100)
            : 0;

        return view('dashboard.user', [

            // ===== STATS =====
            'pendingTasks' => Task::where('user_id', $user->id)
                ->where('status', TaskStatus::OPEN)
                ->count(),

            'inProgressTasks' => Task::where('user_id', $user->id)
                ->where('status', TaskStatus::IN_PROGRESS)
                ->count(),

            'completedTasks' => $completedTasks,

            'todayTasks' => Task::where('user_id', $user->id)
                ->whereDate('created_at', today())
                ->count(),

            'completionRate' => $completionRate,

            // ===== TASK LIST =====
            'myTasks' => Task::where('user_id', $user->id)
                ->latest()
                ->with('project')
                ->take(10)
                ->get(),

        ]);
    }
}
