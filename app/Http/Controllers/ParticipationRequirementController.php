<?php

namespace App\Http\Controllers;

use App\Models\ParticipationRequirement;
use App\Models\Program;
use App\Models\Scout;
use App\Models\Week;
use Illuminate\Http\Request;

class ParticipationRequirementController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('participation_requirements.index')
            ->with('reqs', ParticipationRequirement::all())
            ->with('programs', Program::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $req = new ParticipationRequirement();
        $req->name = $request['name'];
        $req->save();

        return back();
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(ParticipationRequirement $participationRequirement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(ParticipationRequirement $participationRequirement)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateParticipationRequirementRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ParticipationRequirement $participationRequirement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(ParticipationRequirement $participationRequirement)
    {
        $participationRequirement->delete();

        return back()->with('message',
            ['type' => 'success', 'body' => 'Participation Requirement "'.$participationRequirement->name.'" was deleted.']
        );
    }

    public function required(Request $request, string $subcamp, Week $week)
    {
        return view('participation_requirements.required')
            ->with('scouts', $week->scouts()->where('subcamp', $subcamp)->get())
            ->with('reqs', ParticipationRequirement::all())
            ->with(compact('subcamp'));
    }

    public function updateSubcamp(Request $request, string $subcamp, Week $week)
    {
        foreach ($week->scouts()->where('subcamp', $subcamp)->get() as $scout) {
            $scout->participationRequirements()->detach();
        }
        foreach ($request->except(['_token']) as $input => $no) { // shouldn't excluding _token be automatic?
            $assoc = explode('-', $input);
            Scout::find($assoc[0])->participationRequirements()->attach($assoc[1]);
        }

        return back();
    }

    public function updatePrograms(Request $request)
    {
        foreach (Program::all() as $program) {
            $program->participationRequirements()->detach();
        }
        foreach ($request->except(['_token']) as $input => $no) { // shouldn't excluding _token be automatic?
            $assoc = explode('-', $input);
            Program::find($assoc[0])->participationRequirements()->attach($assoc[1]);
        }

        return back();
    }
}
