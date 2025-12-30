<?php

namespace App\Http\Controllers;

use App\Models\Greeting;
use App\Models\Template;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GreetingController extends Controller
{
    public function create(Request $request)
    {
        return view('greetings.create', [
            'occasions' => $this->occasionOptions(),
            'styles' => $this->styleOptions(),
            'templates' => $this->availableTemplates($request),
        ]);
    }

    public function store(Request $request)
    {
        $occasions = $this->occasionOptions();
        $styles = $this->styleOptions();
        $templateRule = Rule::exists('templates', 'id');

        if (! $request->user()->hasRole('admin')) {
            $templateRule = $templateRule->where('user_id', $request->user()->id);
        }

        $data = $request->validate([
            'recipient_name' => 'required|string|max:80',
            'occasion' => 'required|in:' . implode(',', array_keys($occasions)),
            'style' => 'required|in:' . implode(',', array_keys($styles)),
            'message' => 'nullable|string|max:500',
            'template_id' => ['nullable', $templateRule],
            'photos' => 'nullable|array|max:12',
            'photos.*' => 'image|max:4096',
        ]);

        $data['user_id'] = $request->user()->id;
        $greeting = Greeting::create($data);

        $this->storeGreetingPhotos($request, $greeting);

        $baseUrl = rtrim($request->getSchemeAndHttpHost() . $request->getBaseUrl(), '/');
        $shareUrl = $baseUrl . route('greetings.intro', $greeting, false);
        $qrSvg = QrCode::format('svg')
            ->size(360)
            ->margin(2)
            ->generate($shareUrl);

        $qrPath = 'qr-codes/' . $greeting->uuid . '.svg';
        Storage::disk('public')->put($qrPath, $qrSvg);
        $greeting->update(['qr_path' => $qrPath]);

        return redirect()->route('greetings.share', $greeting);
    }

    public function share(Request $request, Greeting $greeting)
    {
        if ($greeting->user_id && $greeting->user_id !== $request->user()->id) {
            abort(403);
        }

        $qrUrl = null;
        if ($greeting->qr_path) {
            $qrUrl = $this->storageUrl($request, $greeting->qr_path);
        }

        $greeting->load('template');
        $styleKey = $greeting->template ? $greeting->template->theme : $greeting->style;

        $baseUrl = rtrim($request->getSchemeAndHttpHost() . $request->getBaseUrl(), '/');
        $shareUrl = $baseUrl . route('greetings.intro', $greeting, false);

        return view('greetings.share', [
            'greeting' => $greeting,
            'occasionLabel' => $this->occasionOptions()[$greeting->occasion] ?? 'Greeting',
            'styleLabel' => $this->styleOptions()[$styleKey] ?? 'Custom Style',
            'shareUrl' => $shareUrl,
            'qrUrl' => $qrUrl,
        ]);
    }

    public function intro(Request $request, Greeting $greeting)
    {
        $greeting->load('template');
        $occasionLabel = $this->occasionOptions()[$greeting->occasion] ?? 'Greeting';
        $templateData = $this->templateDataWithVideo($request, $greeting, $occasionLabel);

        return view('greetings.intro', array_merge($templateData, [
            'greeting' => $greeting,
            'occasionLabel' => $occasionLabel,
        ]));
    }

    public function cake(Request $request, Greeting $greeting)
    {
        $greeting->load('template');
        $occasionLabel = $this->occasionOptions()[$greeting->occasion] ?? 'Greeting';
        $templateData = $this->templateDataWithVideo($request, $greeting, $occasionLabel);

        return view('greetings.cake', array_merge($templateData, [
            'greeting' => $greeting,
            'occasionLabel' => $occasionLabel,
        ]));
    }

    public function wishForm(Request $request, Greeting $greeting)
    {
        $greeting->load('template');
        $occasionLabel = $this->occasionOptions()[$greeting->occasion] ?? 'Greeting';
        $templateData = $this->templateDataWithVideo($request, $greeting, $occasionLabel);

        return view('greetings.wish', array_merge($templateData, [
            'greeting' => $greeting,
            'occasionLabel' => $occasionLabel,
            'wishSent' => session('wish_sent', false),
            'giftOptions' => $this->giftOptions(),
        ]));
    }

    public function storeWish(Request $request, Greeting $greeting)
    {
        $giftOptions = $this->giftOptions();
        $data = $request->validate([
            'sender_name' => 'required|string|max:80',
            'message' => 'required|string|max:500',
            'gift_choice' => ['required', Rule::in(array_keys($giftOptions))],
        ]);

        $wish = $greeting->wishes()->create($data);
        $request->session()->put('wish_id', $wish->id);

        return redirect()
            ->route('greetings.final', $greeting)
            ->with('wish_sent', true);
    }

    public function album(Request $request, Greeting $greeting)
    {
        $greeting->load(['template', 'photos']);
        $occasionLabel = $this->occasionOptions()[$greeting->occasion] ?? 'Greeting';
        $templateData = $this->templateDataWithVideo($request, $greeting, $occasionLabel);
        $photos = $greeting->photos->map(function ($photo) use ($request) {
            return $this->storageUrl($request, $photo->path);
        });

        return view('greetings.album', array_merge($templateData, [
            'greeting' => $greeting,
            'occasionLabel' => $occasionLabel,
            'photos' => $photos,
        ]));
    }

    public function final(Request $request, Greeting $greeting)
    {
        $greeting->load('template');
        $occasionLabel = $this->occasionOptions()[$greeting->occasion] ?? 'Greeting';
        $defaultMessages = $this->defaultMessages();
        $message = $greeting->message ?: ($defaultMessages[$greeting->occasion] ?? 'Wishing you something wonderful.');
        $templateData = $this->templateDataWithVideo($request, $greeting, $occasionLabel);
        $giftOptions = $this->giftOptions();
        $wishId = $request->session()->get('wish_id');
        $wish = null;

        if ($wishId) {
            $wish = $greeting->wishes()->whereKey($wishId)->first();
        }

        if (! $wish) {
            $wish = $greeting->wishes()->latest()->first();
        }

        $giftChoice = $wish ? $wish->gift_choice : null;
        $giftLabel = $giftChoice && isset($giftOptions[$giftChoice])
            ? $giftOptions[$giftChoice]
            : 'Surprise Gift';

        return view('greetings.final', array_merge($templateData, [
            'greeting' => $greeting,
            'occasionLabel' => $occasionLabel,
            'message' => $message,
            'giftChoice' => $giftChoice,
            'giftLabel' => $giftLabel,
            'wishSent' => session('wish_sent', false),
        ]));
    }

    private function templateData(Greeting $greeting, string $occasionLabel): array
    {
        $template = $greeting->template;
        $theme = $template ? $template->theme : ($greeting->style ?: 'spark');
        $fontFamily = $template ? $template->font_family : null;
        $fontUrl = $fontFamily ? Template::fontUrl($fontFamily) : null;
        $bodyStyle = $fontFamily
            ? "--font-display: '{$fontFamily}', serif; --font-body: '{$fontFamily}', sans-serif;"
            : '';
        $tokens = [
            '{name}' => $greeting->recipient_name,
            '{occasion}' => $occasionLabel,
        ];
        $resolve = function (?string $value, string $fallback) use ($tokens) {
            $text = $value ?: $fallback;
            return str_replace(array_keys($tokens), array_values($tokens), $text);
        };

        return [
            'theme' => $theme,
            'fontUrl' => $fontUrl,
            'bodyStyle' => $bodyStyle,
            'introTitle' => $resolve($template ? $template->intro_title : null, 'Welcome {name}'),
            'introSubtitle' => $resolve($template ? $template->intro_subtitle : null, '{occasion} is here'),
            'cakeTitle' => $resolve($template ? $template->cake_title : null, 'Lets celebrate'),
            'cakeSubtitle' => $resolve($template ? $template->cake_subtitle : null, 'Make a wish for {name}'),
            'albumTitle' => $resolve($template ? $template->album_title : null, 'Memory Lane'),
            'albumSubtitle' => $resolve($template ? $template->album_subtitle : null, 'A few moments together'),
            'finalTitle' => $resolve($template ? $template->final_title : null, '{occasion}, {name}'),
            'finalSubtitle' => $resolve($template ? $template->final_subtitle : null, 'Wishing you something wonderful.'),
            'videoPath' => $template ? $template->video_path : null,
        ];
    }

    private function templateDataWithVideo(Request $request, Greeting $greeting, string $occasionLabel): array
    {
        $templateData = $this->templateData($greeting, $occasionLabel);
        $templateData['videoUrl'] = $templateData['videoPath']
            ? $this->storageUrl($request, $templateData['videoPath'])
            : null;
        $templateData['audioTracks'] = $this->audioTracks($request);

        return $templateData;
    }

    private function audioTracks(Request $request): array
    {
        $baseUrl = rtrim($request->getSchemeAndHttpHost() . $request->getBaseUrl(), '/');

        return [
            $baseUrl . '/audio1.mpeg',
            $baseUrl . '/audio2.mpeg',
            $baseUrl . '/audio3.mpeg',
        ];
    }

    private function storageUrl(Request $request, string $path): string
    {
        $baseUrl = rtrim($request->getSchemeAndHttpHost() . $request->getBaseUrl(), '/');
        return $baseUrl . '/storage/' . ltrim($path, '/');
    }

    private function storeGreetingPhotos(Request $request, Greeting $greeting): void
    {
        $photos = $request->file('photos', []);
        if (empty($photos)) {
            return;
        }

        foreach ($photos as $index => $photo) {
            $path = $photo->store('greeting-photos/' . $greeting->uuid, 'public');
            $greeting->photos()->create([
                'path' => $path,
                'sort_order' => $index,
            ]);
        }
    }

    private function availableTemplates(Request $request)
    {
        $query = Template::query()->orderBy('name');

        if (! $request->user()->hasRole('admin')) {
            $query->where('user_id', $request->user()->id);
        }

        return $query->get();
    }

    private function occasionOptions(): array
    {
        return [
            'new_year' => 'Happy New Year',
            'birthday' => 'Birthday',
            'anniversary' => 'Anniversary',
            'wedding' => 'Wedding',
            'congrats' => 'Congratulations',
            'thank_you' => 'Thank You',
            'get_well' => 'Get Well Soon',
            'just_because' => 'Just Because',
        ];
    }

    private function styleOptions(): array
    {
        return [
            'spark' => 'Spark Night',
            'balloons' => 'Balloons',
            'sunrise' => 'Sunrise Bloom',
            'retro' => 'Retro Pop',
            'cinematic' => 'Cinematic Video',
        ];
    }

    private function giftOptions(): array
    {
        return [
            'ring' => 'Ring',
            'necklace' => 'Necklace',
            'bracelet' => 'Bracelet',
            'watch' => 'Watch',
            'perfume' => 'Perfume',
            'flowers' => 'Flowers',
            'chocolate_box' => 'Chocolate Box',
            'teddy_bear' => 'Teddy Bear',
        ];
    }

    private function defaultMessages(): array
    {
        return [
            'new_year' => 'Wishing you a bright and joyful new year.',
            'birthday' => 'Hope your day is full of laughter and good surprises.',
            'anniversary' => 'Celebrating your story and all the days ahead.',
            'wedding' => 'May your journey together be full of warmth and wonder.',
            'congrats' => 'You did it. So proud of you and cheering you on.',
            'thank_you' => 'Thank you for everything you do and the way you show up.',
            'get_well' => 'Sending calm, strength, and a fast recovery.',
            'just_because' => 'A little note to say you matter a lot.',
        ];
    }
}
