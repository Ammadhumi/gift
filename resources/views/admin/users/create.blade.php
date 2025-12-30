@extends('layouts.greetings', [
    'title' => 'Create User',
    'bodyClass' => 'studio theme--studio',
    'showNav' => true,
])

@section('content')
<div class="studio-shell">
    <header class="studio-header">
        <p class="studio-eyebrow">Admin</p>
        <h1>Create a new user</h1>
        <p class="studio-subtitle">Set roles and permissions for the new account.</p>
    </header>

    <form class="admin-form" method="POST" action="{{ route('admin.users.store') }}">
        @csrf

        <div class="field">
            <label for="name">Full name</label>
            <input id="name" name="name" type="text" value="{{ old('name') }}" required>
            @error('name')
            <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="email">Email address</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" required>
            @error('email')
            <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>
            @error('password')
            <p class="field-error">{{ $message }}</p>
            @enderror
        </div>

        <div class="field">
            <label for="password_confirmation">Confirm password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required>
        </div>

        <div class="admin-grid">
            <div>
                <p class="section-title">Roles</p>
                <div class="chip-grid">
                    @foreach ($roles as $role)
                        <label class="chip">
                            <input type="checkbox" name="roles[]" value="{{ $role->name }}" {{ in_array($role->name, old('roles', []), true) ? 'checked' : '' }}>
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
                            <input type="checkbox" name="permissions[]" value="{{ $permission->name }}" {{ in_array($permission->name, old('permissions', []), true) ? 'checked' : '' }}>
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
            <button class="primary-button" type="submit">Create user</button>
            <a class="secondary-button" href="{{ route('admin.users.index') }}">Cancel</a>
        </div>
    </form>
</div>
@endsection
