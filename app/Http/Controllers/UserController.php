<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserStoreRequest;
use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{


    public function index()
    {
        $withDeleted = request('deleted') === 'true';
        $search = request('search');

        $users = User::query()

            ->when($withDeleted, function ($query) {
                $query->onlyTrashed();
            })

            ->when($search, function ($query) use ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('first_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('last_name', 'LIKE', '%' . $search . '%')
                        ->orWhere('email', 'LIKE', '%' . $search . '%');
                });
            })

            ->with('roles')
            ->paginate(10)
            ->withQueryString();

        if (request()->ajax()) {
            return view('users.table', compact('users', 'withDeleted'))->render();
        }

        return view('users.index', compact('users', 'withDeleted'));
    }
    public function create()
    {
        $roles = Role::all();

        return view('users.create', [
            'roles' => $roles,
        ]);
    }

    public function store(UserStoreRequest $request)
    {
        $user = User::create($request->validated());

        $user->save();

        if ($request->has('role')) {
            $user->syncRoles($request->role);
        }

        return redirect()->route('users.index')->with('status', 'User created successfully');
    }

    public function edit($id)
    {
        $user = User::withTrashed()->findOrFail($id);

        $roles = Role::all();
        $user->load('roles');

        return view('users.edit', [
            'user' => $user,
            'roles' => $roles,
        ]);
    }

    public function update(UpdateUserRequest $request, User $user)
    {
        $user->update($request->validated());

        if ($request->has('terms_accepted') && ! $user->terms_accepted_at) {
            $user->terms_accepted_at = now();
            $user->save();
        }

        if ($request->has('role')) {
            $user->syncRoles($request->role);
        }

        return redirect()->route('users.index')->with('status', 'User updated successfully');
    }

    public function destroy(User $user)
    {
        $user->delete(); // soft delete

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully'
        ]);
    }


    // Restore
    public function restore($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->restore();

        return response()->json([
            'success' => true,
            'message' => 'User restored successfully'
        ]);
    }
    // Permanent Delete
    public function forceDelete($id)
    {
        $user = User::withTrashed()->findOrFail($id);
        $user->forceDelete();

        return response()->json([
            'success' => true,
            'message' => 'User permanently deleted'
        ]);
    }
}
