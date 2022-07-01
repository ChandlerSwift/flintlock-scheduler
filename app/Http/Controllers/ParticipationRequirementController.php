<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ParticipationRequirement;
use App\Models\Scout;

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
            ->with('reqs', ParticipationRequirement::all());
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
     * @param  \App\Models\ParticipationRequirement  $participationRequirement
     * @return \Illuminate\Http\Response
     */
    public function show(ParticipationRequirement $participationRequirement)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\ParticipationRequirement  $participationRequirement
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
     * @param  \App\Models\ParticipationRequirement  $participationRequirement
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ParticipationRequirement $participationRequirement)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\ParticipationRequirement  $participationRequirement
     * @return \Illuminate\Http\Response
     */
    public function destroy(ParticipationRequirement $participationRequirement)
    {
        //
    }

    public function required(Request $request, string $subcamp) {
        return view('participation_requirements.required')
            ->with('scouts', Scout::where('subcamp', $subcamp)->get())
            ->with('reqs', ParticipationRequirement::all())
            ->with(compact('subcamp'));
    }

    public function updateSubcamp(Request $request, string $subcamp) {
        foreach (Scout::where('subcamp', $subcamp)->get() as $scout) {
            $scout->participationRequirements()->detach();
        }
        foreach ($request->except(['_token']) as $input => $no) { // shouldn't excluding _token be automatic?
            $assoc = explode('-', $input);
            Scout::find($assoc[0])->participationRequirements()->attach($assoc[1]);
        }
        return back();
    }
}
