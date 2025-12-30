<?php

namespace App\Http\Controllers;

use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class TemplateController extends Controller
{
    public function index(Request $request)
    {
        $query = Template::query()->with('user')->orderBy('name');

        if (! $request->user()->hasRole('admin')) {
            $query->where('user_id', $request->user()->id);
        }

        return view('templates.index', [
            'templates' => $query->get(),
        ]);
    }

    public function create()
    {
        return view('templates.create', [
            'themes' => $this->themeOptions(),
            'fonts' => Template::fontOptions(),
        ]);
    }

    public function store(Request $request)
    {
        $themes = $this->themeOptions();
        $fonts = Template::fontOptions();

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'theme' => 'required|in:' . implode(',', array_keys($themes)),
            'font_family' => 'required|in:' . implode(',', array_keys($fonts)),
            'intro_title' => 'nullable|string|max:200',
            'intro_subtitle' => 'nullable|string|max:400',
            'cake_title' => 'nullable|string|max:200',
            'cake_subtitle' => 'nullable|string|max:400',
            'album_title' => 'nullable|string|max:200',
            'album_subtitle' => 'nullable|string|max:400',
            'final_title' => 'nullable|string|max:200',
            'final_subtitle' => 'nullable|string|max:400',
            'video' => 'nullable|file|mimetypes:video/mp4|max:10240',
        ]);

        $data['user_id'] = $request->user()->id;
        $data['video_path'] = null;

        if ($request->hasFile('video')) {
            $data['video_path'] = $request->file('video')->store('template-videos', 'public');
        }

        Template::create($data);

        return redirect()->route('templates.index');
    }

    public function edit(Request $request, Template $template)
    {
        if (! $request->user()->hasRole('admin') && $template->user_id !== $request->user()->id) {
            abort(403);
        }

        return view('templates.edit', [
            'template' => $template,
            'themes' => $this->themeOptions(),
            'fonts' => Template::fontOptions(),
        ]);
    }

    public function update(Request $request, Template $template)
    {
        if (! $request->user()->hasRole('admin') && $template->user_id !== $request->user()->id) {
            abort(403);
        }

        $themes = $this->themeOptions();
        $fonts = Template::fontOptions();

        $data = $request->validate([
            'name' => 'required|string|max:120',
            'theme' => 'required|in:' . implode(',', array_keys($themes)),
            'font_family' => 'required|in:' . implode(',', array_keys($fonts)),
            'intro_title' => 'nullable|string|max:200',
            'intro_subtitle' => 'nullable|string|max:400',
            'cake_title' => 'nullable|string|max:200',
            'cake_subtitle' => 'nullable|string|max:400',
            'album_title' => 'nullable|string|max:200',
            'album_subtitle' => 'nullable|string|max:400',
            'final_title' => 'nullable|string|max:200',
            'final_subtitle' => 'nullable|string|max:400',
            'video' => 'nullable|file|mimetypes:video/mp4|max:10240',
            'remove_video' => 'nullable|boolean',
        ]);

        if (!empty($data['remove_video']) && $template->video_path) {
            Storage::disk('public')->delete($template->video_path);
            $template->video_path = null;
        }

        if ($request->hasFile('video')) {
            if ($template->video_path) {
                Storage::disk('public')->delete($template->video_path);
            }
            $data['video_path'] = $request->file('video')->store('template-videos', 'public');
        }

        unset($data['remove_video']);

        $template->fill($data);
        $template->save();

        return redirect()->route('templates.index');
    }

    public function destroy(Request $request, Template $template)
    {
        if (! $request->user()->hasRole('admin')) {
            abort(403);
        }

        if ($template->video_path) {
            Storage::disk('public')->delete($template->video_path);
        }

        $template->delete();

        return redirect()->route('templates.index');
    }

    private function themeOptions(): array
    {
        return [
            'spark' => 'Spark Night',
            'balloons' => 'Balloons',
            'sunrise' => 'Sunrise Bloom',
            'retro' => 'Retro Pop',
            'cinematic' => 'Cinematic Video',
        ];
    }
}
