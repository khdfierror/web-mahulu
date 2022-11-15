<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\News;
use Illuminate\Http\Request;
use Inertia\Inertia;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
     public function index(Request $request)
    {
        $filters = array_merge([], $request->only([
            'search',
            'trashed',
        ]));

        $news = News::filter($filters)
            ->paginate()->transform(fn ($news) => [
                'id' => $news->ulid,
                'title' => $news?->title,
                'image' => value(fn ($image) => $image ? [
                    'name' => $image->name,
                    'size' => $image->size,
                    'type' => 'image',
                    'url' => $image->glide_url,
                ] : null, $news?->image?->file),
                'content' => $news?->content,
                'date' => [
                    'time' => $news->date,
                    'string' => $news->date?->translatedFormat('l, j F Y H:i a'),
                    'short' => $news->date?->translatedFormat('j F Y H:i'),
                ],
                'location' => $news?->location,
                'updated_at' => [
                    'time' => $news?->updated_at,
                    'string' => $news?->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $news?->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ]);

        return Inertia::render('Admin/News/Index', [
            'filters' => $filters,
            'params' => [

            ],
            'news' => $news,
        ]);
    }

    protected function form(Request $request, News $news = null)
    {
        return Inertia::render('Admin/News/Form', [
            'news' => [
                'id' => $news?->ulid,
                'title' => $news?->title,
                'image' => value(fn ($image) => $image ? [
                    'name' => $image->name,
                    'size' => $image->size,
                    'type' => 'image',
                    'url' => $image->glide_url,
                ] : null, $news?->image?->file),
                
                'content' => $news?->content,
                'date' => $news?->date,
                'location' => $news?->location,
                'created_at' => [
                    'time' => $news?->created_at,
                    'string' => $news?->created_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $news?->created_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'updated_at' => [
                    'time' => $news?->updated_at,
                    'string' => $news?->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $news?->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ],
            'params' => [

            ],
        ]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        return $this->form($request);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $input = $request->validate([
            'title' => ['required'],
            'image' => ['required', 'file', 'max:2040'],
            'content' => ['required'],
            'date' => ['required'],
            'location' => ['required'],
        ]);

        if ($request->hasFile('image')) {
                $news->updateImage($request->file('image'));
            }

        $news = News::create($input);

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.news.index')
            ->banner('News Created');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function edit(News $news, Request $request)
    {
        return $this->form($request, $news);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function update(News $news, Request $request)
    {
        $input = $request->validate([
            'title' => ['required'],
            'image' => ['required', 'file', 'max:2040'],
            'content' => ['required'],
            'date' => ['required'],
            'location' => ['required'],
        ]);

         $news->update($input);

         if ($request->hasFile('image')) {
                $news->updateImage($request->file('image'));
            }

         return redirect()
            ->route('admin.news.index')
            ->banner('News Updated');
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\News  $news
     * @return \Illuminate\Http\Response
     */
    public function destroy(News $news, Request $request)
    {
        $news->delete();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('News Deleted');
    }

    public function restore(News $news, Request $request)
    {
        $news->restore();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Agenda news');
    }
}
