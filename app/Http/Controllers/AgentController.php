<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAgentRequest;
use App\Http\Requests\UpdateAgentRequest;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class AgentController extends Controller
{
    const USERS_PAGINATE = 9;

    public function list()
    {
        $users = User::paginate(self::USERS_PAGINATE);
        $roles = Role::all();
        return view('agents.list', compact('users', 'roles'));
    }

    public function add(User $user, Role $roles)
    {
        return view('agents.add', [
            'user' => $user,
            'roles' => $roles::all()
        ]);
    }

    public function store(StoreAgentRequest $request)
    {
        $validatedData = $request->validated();

        $validatedData['password'] = Hash::make($request->password);

        $user = User::create($validatedData);

        if (auth()->user()->hasRole('Admin')) {
            $roleName = $request->input('role'); 
            $role = Role::where('name', $roleName)->firstOrFail();
            $user->assignRole($role);
        }
        
        $user->assignRole('Sub_Agent');

        return redirect()->route('agents.list')->with('success', 'User created successfully.');
    }

    public function edit(User $user, $id)
    { 
        $user = User::find($id);
        $roles = Role::all();
        return view('agents.edit', compact('user', 'roles'));
    }

    public function update(UpdateAgentRequest $request, $id, User $user)
    {
        $user = $user->find($id);
        $user->name = $request->input('name');

        if ($request->filled('email')) {
            $user->email = $request->input('email');
        }

        if ($request->filled('password')) {
            $user->password = Hash::make($request->password);
        }

        $user->save();

        return redirect()->route('agents.list')
            ->with('success', 'User updated successfully.');
    }
}
