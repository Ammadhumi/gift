<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class UserController extends Controller
{
    public function index()
    {
        $users = User::with(['roles', 'permissions'])
            ->orderBy('name')
            ->get();

        return view('admin.users.index', [
            'users' => $users,
        ]);
    }

    public function create()
    {
        return view('admin.users.create', [
            'roles' => Role::orderBy('name')->get(),
            'permissions' => Permission::orderBy('name')->get(),
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:80',
            'email' => 'required|string|email|max:120|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'roles' => 'array',
            'roles.*' => 'string|exists:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $user->syncRoles($data['roles'] ?? []);
        $user->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.users.index');
    }

    public function edit(User $user)
    {
        return view('admin.users.edit', [
            'user' => $user->load(['roles', 'permissions']),
            'roles' => Role::orderBy('name')->get(),
            'permissions' => Permission::orderBy('name')->get(),
        ]);
    }

    public function update(Request $request, User $user)
    {
        $data = $request->validate([
            'name' => 'required|string|max:80',
            'email' => [
                'required',
                'string',
                'email',
                'max:120',
                Rule::unique('users', 'email')->ignore($user->id),
            ],
            'password' => 'nullable|string|min:8|confirmed',
            'roles' => 'array',
            'roles.*' => 'string|exists:roles,name',
            'permissions' => 'array',
            'permissions.*' => 'string|exists:permissions,name',
        ]);

        $user->name = $data['name'];
        $user->email = $data['email'];

        if (!empty($data['password'])) {
            $user->password = Hash::make($data['password']);
        }

        $user->save();

        $roles = $data['roles'] ?? [];
        if ($request->user()->is($user) && !in_array('admin', $roles, true)) {
            $roles[] = 'admin';
        }

        $user->syncRoles($roles);
        $user->syncPermissions($data['permissions'] ?? []);

        return redirect()->route('admin.users.index');
    }
}
