@extends('layouts.greetings', [
    'title' => 'Manage Users',
    'bodyClass' => 'studio theme--studio',
    'showNav' => true,
])

@section('content')
<div class="studio-shell">
    <header class="studio-header">
        <p class="studio-eyebrow">Admin</p>
        <h1>Manage users and access</h1>
        <p class="studio-subtitle">Create accounts and assign roles or permissions.</p>
    </header>

    <div class="admin-actions">
        <a class="primary-button" href="{{ route('admin.users.create') }}">Create user</a>
    </div>

    <div class="admin-table">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Roles</th>
                    <th>Permissions</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($users as $user)
                    <tr>
                        <td>{{ $user->name }}</td>
                        <td>{{ $user->email }}</td>
                        <td>{{ $user->roles->pluck('name')->implode(', ') ?: '-' }}</td>
                        <td>{{ $user->permissions->pluck('name')->implode(', ') ?: '-' }}</td>
                        <td>
                            <a class="ghost-button" href="{{ route('admin.users.edit', $user) }}">Edit</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="5">No users found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
