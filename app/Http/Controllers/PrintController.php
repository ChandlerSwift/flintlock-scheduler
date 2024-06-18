<?php

namespace App\Http\Controllers;

use App\Models\Scout;
use App\Models\Session;
use App\Models\Week;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PrintController extends Controller
{
    public function chooseRosters(Request $request)
    {
        $week = Week::find($request->cookie('week_id'));

        return view('print.select_roster_time')
            ->with('start_times', $week->sessions()->select('start_time')->distinct()->orderBy('start_time')->get()->pluck('start_time'));
    }

    public function rosters(Request $request)
    {
        $start_times = [];
        foreach ($request->input('start_times') as $start_time) {
            array_push($start_times, Carbon::createFromTimestamp($start_time));
        }

        return view('print.rosters')
            ->with('sessions', Session::whereIn('start_time', $start_times)->get())
            ->with('scouts', Scout::all());
    }

    public function units(Request $request)
    {
        $bus_times = [
            '09:00' => [
                'Buckskin' => '8:30 AM',
                'Ten Chiefs' => '8:40 AM',
                'Voyageur' => '8:50 AM',
            ],
            '13:00' => [
                'Buckskin' => '12:30 PM',
                'Ten Chiefs' => '12:40 PM',
                'Voyageur' => '12:50 PM',
            ],
            '18:00' => [
                'Buckskin' => '5:30 PM',
                'Ten Chiefs' => '5:40 PM',
                'Voyageur' => '5:50 PM',
            ],
            '19:00' => [
                'Buckskin' => '6:30 PM',
                'Ten Chiefs' => '6:40 PM',
                'Voyageur' => '6:50 PM',
            ],
        ];
        $week = Week::find($request->cookie('week_id'));

        $username = Auth::user()->name;
        if (in_array($username, ["Buckskin", "Ten Chiefs", "Voyageur"])) { // Subcamps only need to print their own scouts
            $scouts = $week->scouts()->where('subcamp', $username)->get();
        } else {
            $scouts = $week->scouts;
        }

        return view('print.units')
            ->with('units', $week->units())
            ->with(compact(['scouts', 'bus_times']));
    }
}
