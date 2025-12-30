@extends('layouts.greetings', [
    'title' => 'Template Studio',
    'bodyClass' => 'studio theme--studio',
    'showNav' => true,
])

@section('content')
<div class="studio-shell">
    <header class="studio-header">
        <p class="studio-eyebrow">Template Studio</p>
        <h1>Create and manage your greeting templates</h1>
        <p class="studio-subtitle">Design the full experience: video intro, cake, wish, album, and final message.</p>
    </header>

    <div class="admin-actions">
        <a class="primary-button" href="{{ route('templates.create') }}">New template</a>
    </div>

    <div class="admin-table">
        <table>
            <thead>
                <tr>
                    <th>Name</th>
                    <th>Theme</th>
                    <th>Font</th>
                    @role('admin')
                        <th>Owner</th>
                    @endrole
                    <th>Video</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                @forelse ($templates as $template)
                    <tr>
                        <td>{{ $template->name }}</td>
                        <td>{{ ucfirst($template->theme) }}</td>
                        <td>{{ $template->font_family }}</td>
                        @role('admin')
                            <td>{{ $template->user->name ?? '-' }}</td>
                        @endrole
                        <td>{{ $template->video_path ? 'Yes' : 'No' }}</td>
                        <td class="table-actions">
                            <a class="ghost-button" href="{{ route('templates.edit', $template) }}">Edit</a>
                            @role('admin')
                                <form method="POST" action="{{ route('templates.destroy', $template) }}" onsubmit="return confirm('Delete this template?')">
                                    @csrf
                                    @method('DELETE')
                                    <button class="ghost-button" type="submit">Delete</button>
                                </form>
                            @endrole
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="{{ auth()->user()->hasRole('admin') ? 6 : 5 }}">No templates yet.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
