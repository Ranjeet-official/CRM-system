<?php

namespace App\Http\Controllers;

use Spatie\Activitylog\Models\Activity;
use App\Models\User;

class ActivityLogController extends Controller
{
    public function index()
    {
        $query = Activity::with('causer')->latest();

        // 🔍 SEARCH (optional - keep if needed)
        if (request('search')) {
            $search = request('search');

            $query->where(function ($q) use ($search) {
                $q->whereHas('causer', function ($q2) use ($search) {
                    $q2->where('first_name', 'like', "%{$search}%");
                })
                    ->orWhere('event', 'like', "%{$search}%")
                    ->orWhere('properties', 'like', "%{$search}%");
            });
        }

        // 👤 USER FILTER
        if (request('user')) {
            $query->where('causer_id', request('user'));
        }

        // 🎯 ACTION FILTER
        if (request('event')) {
            $query->where('event', request('event'));
        }

        $logs = $query->paginate(5);

        $users = User::pluck('first_name', 'id');

        // AJAX
        if (request()->ajax()) {
            return view('activity-log.table', compact('logs'))->render();
        }

        return view('activity-log.index', compact('logs', 'users'));
    }
}
