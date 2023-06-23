<?php

namespace App\Http\Controllers;

use App\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function list()
    {
        return view('programs.index')->with('programs', Program::all());
    }

    public function showAll()
    {
        return view('programs.all')->with('programs', Program::all());
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('admin.programs')->with('programs', Program::all());
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $program = Program::create($request->all());
        $program->save();

        return back()->with('message',
            ['type' => 'success', 'body' => 'Program "'.$program->name.'" saved successfully.']
        );
    }

    /**
     * Display the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Program $program)
    {
        return view('programs.show')->with('program', $program);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Program $program)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Program $program)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Program $program)
    {
        $program->delete();

        return back()->with('message',
            ['type' => 'success', 'body' => 'Program "'.$program->name.'" deleted successfully.']
        );
    }
}
