<?php

namespace App\Http\Controllers;

use App\Models\Scout;
use Illuminate\Http\Request;

class ScoutController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('admin.scouts')
            ->with('scouts', Scout::where('week_id', $request->cookie('week_id'))->get());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('scouts.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $scout = Scout::create($request->all());
        $scout->save();
        return back()->with('message', "Scout saved successfully.");
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Scout  $scout
     * @return \Illuminate\Http\Response
     */
    public function show(Scout $scout)
    {
        return view('scouts.show', ['scout' => $scout]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Scout  $scout
     * @return \Illuminate\Http\Response
     */
    public function edit(Scout $scout)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Scout  $scout
     * @return \Illuminate\Http\Response
     */
    public function updateReqs(Request $request, Scout $scout)
    {
        $scout->participationRequirements()->sync($request->pr);
        return back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Scout  $scout
     * @return \Illuminate\Http\Response
     */
    public function destroy(Scout $scout)
    {
        //
    }

    public function search(Request $request){
        // Get the search value from the request
        $search = $request->input('search');
    
        // Search in the title and body columns from the posts table
        $searchResults = Scout::query()
            ->where('first_name', 'LIKE', "%{$search}%")
            ->orWhere('last_name', 'LIKE', "%{$search}%")
            ->orWhere('unit', 'LIKE', "%{$search}%")
            ->orWhere('site', 'LIKE', "%{$search}%")
            ->get();
    
        // Return the search view with the resluts compacted
        return view('search', compact('searchResults'));
    }
}
