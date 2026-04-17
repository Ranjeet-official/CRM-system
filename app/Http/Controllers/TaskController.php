<?php

namespace App\Http\Controllers;

use App\Enums\TaskStatus;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
// use App\Mail\MailTaskAssigned;
use App\Models\Client;
use App\Models\Project;
use App\Models\Task;
use App\Models\User;
use App\Notifications\TaskAssigned;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Mail;

class TaskController extends Controller
{
    public function index()
    {
        $search = request('search');

        $query = Task::with(['user', 'client', 'project']);

        if (!auth()->user()->hasRole('admin')) {
            $query->where('user_id', auth()->id());
        }

        if ($search) {
            $query->where(function ($q) use ($search) {

                // main fields
                $q->where('title', 'LIKE', "%$search%")
                    ->orWhere('description', 'LIKE', "%$search%")

                    // ✅ client (correct column)
                    ->orWhereHas('client', function ($c) use ($search) {
                        $c->where('contact_name', 'LIKE', "%$search%");
                    })

                    // ✅ project
                    ->orWhereHas('project', function ($p) use ($search) {
                        $p->where('title', 'LIKE', "%$search%");
                    })

                    ->orWhereHas('user', function ($u) use ($search) {
                        $u->where('first_name', 'LIKE', "%$search%")
                            ->orWhere('last_name', 'LIKE', "%$search%");
                    });
            });
        }

        $tasks = $query->paginate(10)->withQueryString();

        // 🔥 AJAX
        if (request()->ajax()) {
            return view('tasks.table', compact('tasks'))->render();
        }

        return view('tasks.index', compact('tasks'));
    }

    public function create(): View
    {
        $users = User::all()->append('full_name');
        $clients = Client::all();
        $projects = Project::all();
        $statuses = TaskStatus::cases();

        return view('tasks.create', [
            'users' => $users,
            'clients' => $clients,
            'projects' => $projects,
            'statuses' => $statuses,
        ]);
    }

    public function store(StoreTaskRequest $request): RedirectResponse
    {
        // RULE 1: project must be OPEN
        $project = Project::find($request->project_id);

        if ($project->status !== \App\Enums\ProjectStatus::OPEN) {
            return redirect()->back()->with('error', 'Project must be OPEN to assign task');
        }

        $task = Task::create($request->validated());

        $user = User::find($request->user_id);

        if ($user && $user->id !== auth()->id()) {
            $user->notify(new TaskAssigned($task));
        }

        return redirect()->route('tasks.index')->with('status', 'Task created successfully');
    }

    public function show(Task $task): View
    {
        $task->load([
            'user' => fn($query) => $query->withTrashed(),
            'client',
            'project',
        ]);

        return view('tasks.show', ['task' => $task]);
    }

    public function edit(Task $task): View
    {
        $users = User::all()->append('full_name');
        $clients = Client::all();
        $projects = Project::all();
        $statuses = TaskStatus::cases();

        return view('tasks.edit', [
            'task' => $task,
            'users' => $users,
            'clients' => $clients,
            'projects' => $projects,
            'statuses' => $statuses,
        ]);
    }

    public function update(UpdateTaskRequest $request, Task $task): RedirectResponse
    {
        $project = $task->project;

        // RULE 1: project must be OPEN to assign
        if ($request->user_id && $project->status !== \App\Enums\ProjectStatus::OPEN) {
            return redirect()->back()->with('error', 'Cannot assign task because project is not OPEN');
        }

        // RULE 2: must be assigned to complete
        if ($request->status === TaskStatus::COMPLETED->value && !$request->user_id) {
            return redirect()->back()->with('error', 'Task must be assigned before completing');
        }

        if ($task->user_id !== $request->user_id) {
            $user = User::find($request->user_id);
        }

        $task->update($request->validated());

        return redirect()->route('tasks.index')
            ->with('status', 'Task updated successfully');
    }

    public function destroy(Task $task)
    {
        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully'
        ]);
    }
}
