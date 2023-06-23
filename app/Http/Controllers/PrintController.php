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
            '15:00' => [
                'Buckskin' => '2:30 PM',
                'Ten Chiefs' => '2:40 PM',
                'Voyageur' => '2:50 PM',
            ],
            '17:00' => [
                'Buckskin' => '4:30 PM',
                'Ten Chiefs' => '4:40 PM',
                'Voyageur' => '4:50 PM',
            ],
            '19:00' => [
                'Buckskin' => '6:40 PM',
                'Ten Chiefs' => '6:30 PM',
                'Voyageur' => '6:20 PM',
            ],
        ];
        $week = Week::find($request->cookie('week_id'));
        if (Auth::user()->admin) {
            $scouts = $week->scouts;
        } else {
            $scouts = $week->scouts()->where('subcamp', Auth::user()->name)->get();
        }

        return view('print.units')
            ->with('units', $week->units())
            ->with(compact(['scouts', 'bus_times']));
    }
}
