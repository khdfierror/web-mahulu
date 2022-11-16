<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Inertia\Inertia;
use App\Models\News as Berita;

class BeritaController extends Controller
{
    public function index(Request $request)
    {
        $filters = array_merge([], $request->only([
            'title',
            'image',
            'content',
            
        ]));

        // $authors = Author::get()->transform(fn ($author) => [
        //     'id' => $author->ulid,
        //     'label' => $author->name,
        // ]);

        // $categories = Category::get()->transform(fn ($category) => [
        //     'id' => $category->ulid,
        //     'label' => $category->name,
        // ]);
        // $category = $request->get('category') ?: 'warta';

        // $tags = Tag::orderBy('name')->pluck('name');

       $berita = Berita::filter($filters)
       ->latest()
            ->paginate(6)->transform(fn ($berita) => [
                'id' => $berita->ulid,
                'title' => $berita?->title,
                'image' => $berita?->image?->file?->glide_url,
                'content' => $berita?->content,
                // 'date' => [
                //     'time' => $berita->date,
                //     'string' => $berita->date?->translatedFormat('l, j F Y H:i a'),
                //     'short' => $berita->date?->translatedFormat('j F Y H:i'),
                // ],
                // 'location' => $berita?->location,
                'updated_at' => [
                    'time' => $berita?->updated_at,
                    'string' => $berita?->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $berita?->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ]);

            
        return Inertia::render('Web/BeritaGrid', [
            'filters' => $filters,
            'params' => [
                // 'authors' => $authors,
                // 'categories' => $categories,
                // 'tags' => $tags,
                // 'category' => ucfirst($category),
            ],
            'berita' => $berita,
        ]);
    }

    public function show(Berita $berita, Request $request)
    {
        // $authors = Author::get()->transform(fn ($author) => [
        //     'id' => $author->ulid,
        //     'label' => $author->name,
        // ]);

        // $categories = Category::get()->transform(fn ($category) => [
        //     'id' => $category->ulid,
        //     'label' => $category->name,
        // ]);

        // $tags = Tag::orderBy('name')->pluck('name');

        return Inertia::render('Web/Berita', [
            'berita' => [
                'title' => $berita?->title,
                'image' => $berita?->image?->file?->glide_url,
                'content' => $berita?->content,
            
             
            
                'updated_at' => $berita?->updated_at?->format('Y-m-d'),
            ],
            'params' => [
                // 'authors' => $authors,
                // 'categories' => $categories,
                // 'tags' => $tags,

                  
            ],
        ]);
    }
}
