<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Aplikasi;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AplikasiController extends Controller
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

        $aplikasi = Aplikasi::filter($filters)
            ->paginate()->transform(fn ($aplikasi) => [
                'id' => $aplikasi->ulid,
                'name' => $aplikasi?->name,
                'image' => value(fn ($image) => $image ? [
                    'name' => $image->name,
                    'size' => $image->size,
                    'type' => 'image',
                    'url' => $image->glide_url,
                ] : null, $aplikasi?->image?->file),
                'link' => $aplikasi?->link,
                'updated_at' => [
                    'time' => $aplikasi?->updated_at,
                    'string' => $aplikasi?->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $aplikasi?->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ]);

        return Inertia::render('Admin/Aplikasi/Index', [
            'filters' => $filters,
            'params' => [

            ],
            'aplikasi' => $aplikasi,
        ]);
    }

    protected function form(Request $request, Aplikasi $aplikasi = null)
    {
        return Inertia::render('Admin/Aplikasi/Form', [
            'aplikasi' => [
                'id' => $aplikasi?->ulid,
                'name' => $aplikasi?->name,
                'image' => value(fn ($image) => $image ? [
                    'name' => $image->name,
                    'size' => $image->size,
                    'type' => 'image',
                    'url' => $image->glide_url,
                ] : null, $aplikasi?->image?->file),
                
                'link' => $aplikasi?->link,
                'created_at' => [
                    'time' => $aplikasi?->created_at,
                    'string' => $aplikasi?->created_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $aplikasi?->created_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'updated_at' => [
                    'time' => $aplikasi?->updated_at,
                    'string' => $aplikasi?->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $aplikasi?->updated_at?->isoFormat('DD MMMM Y kk:mm'),
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
            'name' => ['required'],
            'image' => ['required', 'file', 'max:2040'],
            'link' => ['required'],
        ]);
        
        $aplikasi = Aplikasi::create($input);
        
        if ($request->hasFile('image')) {
                $aplikasi->updateImage($request->file('image'));
            }


        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.aplikasi.index')
            ->banner('Aplikasi Created');
    }


    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Aplikasi  $apl
     * @return \Illuminate\Http\Response
     */
    public function edit(Aplikasi $aplikasi, Request $request)
    {
        return $this->form($request, $aplikasi);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Apl  $apl
     * @return \Illuminate\Http\Response
     */
    public function update(Aplikasi $aplikasi, Request $request)
    {
        $input = $request->validate([
            'name' => ['required'],
            'image' => ['required', 'file', 'max:2040'],
            'link' => ['required'],
        ]);

         $aplikasi->update($input);

         if ($request->hasFile('image')) {
                $aplikasi->updateImage($request->file('image'));
            }

         return redirect()
            ->route('admin.aplikasi.index')
            ->banner('Aplikasi Updated');
        
    }

    public function destroy(Aplikasi $aplikasi, Request $request)
    {
        $aplikasi->delete();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Aplikasi Deleted');
    }

    public function restore(Aplikasi $aplikasi, Request $request)
    {
        $aplikasi->restore();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('...');
    }
}
