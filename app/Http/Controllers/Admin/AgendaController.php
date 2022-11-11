<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Agenda;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AgendaController extends Controller
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

        $agenda = Agenda::filter($filters)
            ->paginate()->transform(fn ($agenda) => [
                'id' => $agenda->ulid,
                'title' => $agenda->title,
                'date' => [
                    'time' => $agenda->date,
                    'string' => $agenda->date?->translatedFormat('l, j F Y H:i a'),
                    'short' => $agenda->date?->translatedFormat('j F Y H:i'),
                ],
                'location' => $agenda->location,
                'participant' => $agenda->participant,
                'updated_at' => [
                    'time' => $agenda->updated_at,
                    'string' => $agenda->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $agenda->updated_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
            ]);

        return Inertia::render('Admin/Agenda/Index', [
            'filters' => $filters,
            'params' => [

            ],
            'agenda' => $agenda,
        ]);
    }

    protected function form(Request $request, Agenda $agenda = null)
    {
        return Inertia::render('Admin/Agenda/Form', [
            'agenda' => [
                'id' => $agenda?->ulid,
                'title' => $agenda?->title,
                'date' => $agenda?->date,
                'location' => $agenda?->location,
                'participant' => $agenda?->participant,
                'created_at' => [
                    'time' => $agenda?->created_at,
                    'string' => $agenda?->created_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $agenda?->created_at?->isoFormat('DD MMMM Y kk:mm'),
                ],
                'updated_at' => [
                    'time' => $agenda?->updated_at,
                    'string' => $agenda?->updated_at?->isoFormat('dddd, DD MMMM Y H:mm z'),
                    'short' => $agenda?->updated_at?->isoFormat('DD MMMM Y kk:mm'),
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
            'date' => ['required'],
            'location' => ['required'],
            'participant' => ['nullable'],
        ]);

        $agenda = Agenda::create($input);

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.agenda.index')
            ->banner('Agenda Created');
 
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    
    public function edit(Agenda $agenda, Request $request)
    {
        return $this->form($request, $agenda);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Agenda $agenda)
    {
        $input = $request->validate([
            'title' => ['required'],
            'date' => ['required'],
            'location' => ['required'],
            'participant' => ['nullable'],
        ]);

        $agenda->update($input);

        /* @phpstan-ignore-next-line */
        return redirect()
            ->route('admin.agenda.index')
            ->banner('Agenda Created');
 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function destroy(Agenda $agenda, Request $request)
    {
        $agenda->delete();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Agenda Deleted');
    }

    public function restore(Agenda $agenda, Request $request)
    {
        $agenda->restore();

        /* @phpstan-ignore-next-line */
        return back()
            ->banner('Agenda Restored');
    }
}
