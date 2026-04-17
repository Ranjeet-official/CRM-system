<?php

namespace App\Http\Controllers;

use App\Enums\ProjectStatus;
use App\Enums\TaskStatus;
use App\Http\Requests\StoreProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Models\Client;
use App\Models\Project;
use App\Models\User;
use App\Notifications\ProjectAssigned;
use Illuminate\Support\Facades\Gate;
use Symfony\Component\HttpFoundation\Response;


class ProjectController extends Controller
{

    public function index()
    {
        $search = request('search');

        $query = Project::with(['client', 'user'])

            ->when(!auth()->user()->hasRole('admin'), function ($q) {
                $q->where('user_id', auth()->id());
            })

            ->when($search, function ($q) use ($search) {

                $q->where(function ($sub) use ($search) {

                    $sub->where('title', 'LIKE', '%' . $search . '%')
                        ->orWhere('description', 'LIKE', '%' . $search . '%')

                        ->orWhereHas('client', function ($c) use ($search) {
                            $c->where('contact_name', 'LIKE', '%' . $search . '%')
                                ->orWhere('contact_email', 'LIKE', '%' . $search . '%');
                        })

                        ->orWhereHas('user', function ($u) use ($search) {
                            $u->where('first_name', 'LIKE', '%' . $search . '%')
                                ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                                ->orWhere('email', 'LIKE', '%' . $search . '%');
                        });
                });
            });

        $projects = $query->paginate(20)->withQueryString();

        if (request()->ajax()) {
            return view('projects.table', compact('projects'))->render();
        }

        return view('projects.index', compact('projects'));
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
        // Step 1: old status
        $oldStatus = $project->status;

        // Step 2: update project
        $project->update($request->validated());

        // Step 3: if project CLOSED → close active tasks
        if ($oldStatus !== ProjectStatus::CLOSED && $project->status === ProjectStatus::CLOSED) {

            $project->tasks()
                ->whereIn('status', [
                    TaskStatus::OPEN->value,
                    TaskStatus::IN_PROGRESS->value
                ])
                ->update([
                    'status' => TaskStatus::COMPLETED->value
                ]);
        }

        // Step 4: if project CANCELLED → cancel all tasks
        if ($oldStatus !== ProjectStatus::CANCELLED && $project->status === ProjectStatus::CANCELLED) {

            $project->tasks()->update([
                'status' => TaskStatus::CANCELLED->value
            ]);
        }

        return redirect()->route('projects.index')
            ->with('status', 'Project updated successfully');
    }
    public function destroy(Project $project)
    {
        $project->delete();

        return response()->json([
            'message' => 'Project deleted successfully'
        ]);
    }
}
