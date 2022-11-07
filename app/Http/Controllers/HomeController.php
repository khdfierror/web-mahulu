<?php

namespace App\Http\Controllers;

use App\Models\CagarBudaya;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Kedeka\InertiaBlog\Models\Post;
use Kedeka\InertiaSetting\Facades\Setting;
use Kedeka\InertiaSlider\Model as Slider;
use Kedeka\InertiaSurvey\Survey;

class HomeController extends Controller
{
    public function __invoke()
    {
        $survey = app(Survey::class)->active();
        $services = Setting::get('service');
        $about = Setting::get('about');

        $posts = Post::with('category', 'image')
        ->latest()
        ->whereNotNull('published_at')
        ->get()->transform(fn ($item) => [
            'id' => $item->ulid,
            'title' => $item->title,
            'slug' => $item->slug,
            'content' => Str::words($item->content, 50, ' ...'),
            'category' => $item->category?->slug,
            'image' => $item->image?->file?->glide_url,
            'published_at' => $item->published_at,
        ]);

        $warta = $posts->filter(fn ($item) => $item['category'] === 'warta')->values()->take(3);
        $jendela = $posts->filter(fn ($item) => $item['category'] === 'jendela')->values()->all();
        $map_options = config('map');

        $map_markers = CagarBudaya::with('address')->whereHas('address', fn ($q) => $q->whereNotNull('lat')->whereNotNull('lng'))->get()->transform(fn ($item) => [
            'location' => [$item->address?->lat, $item->address?->lng],
            'popupText' => $item->name,
            'info' => 'cp name : '.$item->cp_name.PHP_EOL.
                    'cp phone : '.$item->cp_phone.PHP_EOL.
                    'cp email : '.$item->cp_email,
        ]);

        $slider = Slider::orderBy('order')->get()->transform(fn ($item) => [
            'order' => $item->order,
            'title' => $item->title,
            'description' => $item->description,
            'link' => $item->link,
            'image' => value(fn ($image) => $image ? [
                'name' => $image->name,
                'size' => $image->size,
                'type' => 'image',
                'url' => $image->glide_url,
            ] : null, $item?->image?->file),
        ]);

        return Inertia::render('Web/Home', [
            'survey' => $survey,
            'services' => $services,
            'warta' => $warta,
            'jendela' => $jendela,
            'facebook' => [
                'url' => 'https://www.facebook.com/plugins/page.php?href=https%3A%2F%2Fwww.facebook.com%2F'.env('FB_PAGE_NAME', 'BPCBKALIMANTAN').'&tabs=timeline&small_header=true&adapt_container_width=true&hide_cover=false&show_facepile=true&appId='.env('FB_APP_ID'),
                'appid' => env('FB_APP_ID'),
                'pagename' => env('FB_PAGE_NAME', 'BPCBKALIMANTAN'),
            ],
            'map_options' => $map_options,
            'map_markers' => $map_markers,
            'slider' => $slider,
            'about' => [
                'title' => data_get($about, 'title'),
                'description' => data_get($about, 'description'),
                'link' => data_get($about, 'link'),
                'photo' => [
                    'name' => json_decode(data_get($about, 'photo'))?->name,
                    'type' => json_decode(data_get($about, 'photo'))?->type,
                    'path' => json_decode(data_get($about, 'photo'))?->path,
                    'url' => json_decode(data_get($about, 'photo'))?->url,
                ],
            ],
        ]);
    }
}
