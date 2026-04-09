<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;
// use App\Notifications\ProjectAssigned;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;

class ProjectController extends Controller
{

    public function index()
    {
        $query = Project::with(['client', 'user']);

        if (!auth()->user()->hasRole('admin')) {
            $query->where('user_id', auth()->id());
        }

        $projects = $query->paginate(20);

        return view('projects.index', ['projects' => $projects]);
    }


    public function create()
    {
        $users = User::all()->append('full_name');
        $clients = Client::all();
        $statuses = ProjectStatus::cases();

        return view('projects.create', [
            'users' => $users,
            'clients' => $clients,
            'statuses' => $statuses,
        ]);
    }

    public function store(StoreProjectRequest $request)
    {
        $project = Project::create($request->validated());
        // $user = User::find($request->user_id);

        // $user->notify(new ProjectAssigned($project));

        return redirect()->route('projects.index');
    }

    public function show(Project $project)
    {
        $project->load([
            'tasks',
            'tasks.user' => fn($query) => $query->withTrashed(), // soft-deleted task users
            'user' => fn($query) => $query->withTrashed(),        // project owner (if soft-deleted)
            'client'
        ]);

        return view('projects.show', ['project' => $project]);
    }

    public function edit(Project $project)
    {
        $users = User::all()->append('fullName');
        $clients = Client::all();
        $statuses = ProjectStatus::cases();

        return view('projects.edit', [
            'project' => $project,
            'users' => $users,
            'clients' => $clients,
            'statuses' => $statuses,
        ]);
    }

    public function update(UpdateProjectRequest $request, Project $project)
    {
        if ($project->user_id !== $request->user_id) {
            // notification logic (optional)
        }

        // ✅ Update project
        $project->update($request->validated());

        // 🔥 If project cancelled → cancel all tasks
        if ($project->status === 'cancelled') {
            $project->tasks()->update([
                'status' => 'cancelled'
            ]);
        }

        return redirect()->route('projects.index')
            ->with('status', 'Project updated successfully');
    }

    public function destroy(Project $project)
    {
        abort_if(Gate::denies('delete'), Response::HTTP_FORBIDDEN, '403 Forbidden');

        try {
            $project->delete();
        } catch (\Illuminate\Database\QueryException $e) {
            if ($e->getCode() === '23000') {
                return redirect()->back()->with('status', 'Project belongs to task. Cannot delete.');
            }
        }

        return redirect()->route('projects.index');
    }
}
