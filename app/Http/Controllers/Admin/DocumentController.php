<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Document;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DocumentController extends Controller
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

        $document = Document::filter($filters)
            ->paginate()->transform(fn ($document) => [
                'id' => $document->ulid,
                'title' => $document?->title,
                'description' => $document?->description,
                'image' => value(fn ($image) => $image ? [
                    'name' => $image->name,
                    'size' => $image->size,
                    'type' => 'image',
                    'url' => $image->glide_url,
                ] : null, $document?->image?->file),

                'file' => value(fn ($file) => $file ? [
                    'name' => $file->name,
                    'size' => $file->size,
                    'type' => 'file',
                    'url' => $file->glide_url,
                ] : null, $document?->file?->file),

                'updated_at' => [
                    'time' => $document?->updated_at,
                    'string' => $document?->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $document?->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ]);

        return Inertia::render('Admin/Document/Index', [
            'filters' => $filters,
            'params' => [],
            'document' => $document,
        ]);
    }

    protected function form(Request $request, Document $document = null)
    {
        return Inertia::render('Admin/Document/Form', [
            'document' => [
                'id' => $document?->ulid,
                'title' => $document?->title,
                'description' => $document?->description,
                'image' => value(fn ($image) => $image ? [
                    'name' => $image->name,
                    'size' => $image->size,
                    'type' => 'image',
                    'url' => $image->glide_url,
                ] : null, $document?->image?->file),

                'file' => value(fn ($file) => $file ? [
                    'name' => $file->name,
                    'size' => $file->size,
                    'type' => 'file',
                    'url' => $file->glide_url,
                ] : null, $document?->file?->file),

                'created_at' => [
                    'time' => $document?->created_at,
                    'string' => $document?->created_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $document?->created_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'updated_at' => [
                    'time' => $document?->updated_at,
                    'string' => $document?->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $document?->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ],
            'params' => [],
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
            'description' => ['required'],
            'image' => ['required', 'file', 'max:2040'],
            'file' => ['required', 'file', 'max:2040'],
        ]);

        $document = Document::create($input);

        if ($request->hasFile('image')) {
            $document->updateImage($request->file('image'));
        }

        if ($request->hasFile('file')) {
            $document->updateFile($request->file('file'));
        }

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.document.index')
            ->banner('Document Created');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function edit(Document $document, Request $request)
    {
        return $this->form($request, $document);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Document  $document
     * @return \Illuminate\Http\Response
     */
    public function update(Document $document, Request $request)
    {
        $input = $request->validate([
            'title' => ['required'],
            'description' => ['required'],
            'image' => ['required', 'file', 'max:2040'],
            'file' => ['required', 'file', 'max:2040'],
        ]);

        $document->update($input);

        if ($request->hasFile('image')) {
            $document->updateImage($request->file('image'));
        }

        if ($request->hasFile('file')) {
            $document->updateFile($request->file('file'));
        }

        return redirect()
            ->route('admin.document.index')
            ->banner('Document Updated');
    }

    public function destroy(Document $document, Request $request)
    {
        $document->delete();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Document Deleted');
    }

    public function restore(Document $document, Request $request)
    {
        $document->restore();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('...');
    }
}
