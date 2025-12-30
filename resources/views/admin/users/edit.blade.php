@extends('layouts.greetings', [
    'title' => 'Edit User',
    'bodyClass' => 'studio theme--studio',
    'showNav' => true,
])

@section('content')
<div class="studio-shell">
    <header class="studio-header">
        <p class="studio-eyebrow">Admin</p>
        <h1>Edit user access</h1>
        <p class="studio-subtitle">Update roles, permissions, or reset the password.</p>
    </header>

    <form class="admin-form" method="POST" action="{{ route('admin.users.update', $user) }}">
        @csrf
        @method('PUT')

        <div class="field">
            <label for="name">Full name</label>
            <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}" required>
            @error('name')
            <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="email">Email address</label>
            <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}" required>
            @error('email')
            <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="password">New password (optional)</label>
            <input id="password" name="password" type="password">
            @error('password')
            <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Confirm new password</label>
            <input id="password_confirmation" name="password_confirmation" type="password">
        </div>

        <div class="admin-grid">
            <div>
                <p class="section-title">Roles</p>
                <div class="chip-grid">
                    @foreach ($roles as $role)
                        <label class="chip">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}"
                                {{ in_array($role->name, old('roles', $user->roles->pluck('name')->all()), true) ? 'checked' : '' }}>
                            <span>{{ $role->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('roles')
                <p class="field-error">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <p class="section-title">Permissions</p>
                <div class="chip-grid">
                    @foreach ($permissions as $permission)
                        <label class="chip">
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}"
                                {{ in_array($permission->name, old('permissions', $user->permissions->pluck('name')->all()), true) ? 'checked' : '' }}>
                            <span>{{ $permission->name }}</span>
                        </label>
                    @endforeach
                </div>
                @error('permissions')
                <p class="field-error">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <div class="admin-actions">
            <button class="primary-button" type="submit">Save changes</button>
            <a class="secondary-button" href="{{ route('admin.users.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection
