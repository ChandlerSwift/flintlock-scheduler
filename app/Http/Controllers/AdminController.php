<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Scout;
use App\Models\Preference;
use App\Models\Program;
use App\Models\Session;
use App\Models\Week;
use Illuminate\Support\Facades\Log;
use League\Csv\Reader;

class AdminController extends Controller
{
    public function import_data(Request $request) {
        if (!$request->spreadsheet) {
            return back()->with('message',
                ["type" => "warning", "body" => "No spreadsheet selected."]
            );
        }
        $spreadsheet = IOFactory::load($request->spreadsheet->path());
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        foreach($sheetData as $row){
            if ($row['C'] == null) { // Name should never be empty, so this is an empty row. Skip it.
                continue;
            }
            if ($row['C'] == 'First Name') { // Don't count the first line
                continue;
            }

            $pre_existing_scout = Scout::where('first_name', $row['C'])
                ->where('last_name', $row['D'])
                ->where('unit', $row['E'])
                ->where('week_id', $request->week_id)
                ->first();
            if ($pre_existing_scout) {
                continue;
            }
            $scout = new Scout;
            $scout->week_id = $request->week_id;
            $scout->first_name = $row['C'];
            $scout->last_name = $row['D'];
            if ($row['G'] != null)
                if ($row['G'] == 'Scout')
                    $scout->rank = 0;
                else if ($row['G'] == 'Tenderfoot')
                    $scout->rank = 1;
                else if ($row['G'] == 'Second Class')
                    $scout->rank = 2;
                else if ($row['G'] == 'First Class')
                    $scout->rank = 3;
                else if ($row['G'] == 'Star')
                    $scout->rank = 4;
                else if ($row['G'] == 'Life')
                    $scout->rank = 5;
                else if ($row['G'] == 'Eagle')
                    $scout->rank = 6;
                else
                    Log::warning("Unknown rank for scout " . $row['C'] . " " . $row['D'] . ", unit " . $row['E'] . " (setting to Scout)");
            if ($row['H'] != null)
                $scout->age = $row['H'];
            else   
                $scout->age = '10';
            if ($row['E'] != null)
                $scout->unit = $row['E'];
            else
                $scout->unit = '0000';
            if ($row['A'] != null)
                $scout->subcamp = $row['A'];
            if ($row['J'] != null)
                $scout->site = $row['J'];
            if ($row['F'] != null)
                $scout->gender = $row['F'];
            else
                $scout->gender = '0';
            $scout->save();
            
            $row_indices = ["K", "L", "M", "N"];
            for ($i = 0; $i < 4; $i++) {
                if ($row[$row_indices[$i]] != "") {
                    $program = Program::where('name', $row[$row_indices[$i]])->first();//TODO change program values
                    if ($program == null) {
                        Log::warning("trying to find nonexistent program " . $row[$row_indices[$i]]);
                    } else {
                        $preference = new Preference;
                        $preference->program_id = $program->id;
                        $preference->rank = $i + 1;
                        $preference->scout_id = $scout->id;
                        $preference->save();
                    }
                }
            }
        }
        return back()->with('message',
            ["type" => "success", "body" => "Data import for week " . Week::find($request->week_id)->name . " was successful."]
        );
    }

    public function import_event_data(Request $request) {
        if (!$request->csv) {
            return back()->with('message',
                ["type" => "warning", "body" => "No csv selected."]
            );
        }

        $reader = Reader::createFromPath($request->csv->path(), 'r');
        $reader->setHeaderOffset(0);
        $records = $reader->getRecords();
        foreach ($records as $offset => $record) {
            Log::info($record);
            if (!str_contains($record['Session 1'], 'Tier 2')) {
                continue;
            }
            $scout = Scout::where('first_name', $record['First Name'])
                ->where('last_name', $record['Last Name'])
                ->where('week_id', $request->week_id)
                ->get()
                ->first(function($scout, $index) use ($record){
                    return str_contains($scout->unit, $record['Unit Nbr.']);
                });
            if (!$scout) {
                throw new \Exception("Could not find scout for \"" . $record['First Name'] . '" "' . $record['Last Name'] . '" (Unit ' . $record['Unit Nbr.'] . ')');
            }
            if (str_contains($record['Session 1'], 'Watersports Outpost')) {
                $program_name = "Water Sports Outpost";
            } elseif (str_contains($record['Session 1'], 'Older Scout Adventure Blast')) {
                $program_name = "Older Scout Adventure Blast";
            } elseif (str_contains($record['Session 1'], 'Mountain Bike Outpost')) {
                $program_name = "Mountain Bike Outpost";
            } else {
                throw new \Exception("Unknown program" . $record['Session 1']);
            }

            $session = Program::where('name', $program_name)->first()
                ->sessions()->where('week_id', $request->week_id)->first();
            if (!$session) {
                throw new \Exception("Could not find session for $program_name");
            }
            $scout->sessions()->syncWithoutDetaching($session->id);
        }

        return back()->with('message',
            [
                "type" => "success",
                "body" => "Import successful.",
            ]
        );
    }

    public function plan_week(Request $request, Week $week) {
        $scouts = $week->scouts()->orderByDesc('age', 'rank')->get();
        $still_filling = true;
        echo "Adding scouts to session...";
        $i=0; 
        while($still_filling){
            echo $i++;
            $still_filling = false;
            foreach($scouts as $scout) {
                if ($this->put_scout_in_session($scout, $week)) {
                    $still_filling = true;
                }
            }
        }
        $satisfied_preference_count = $week->preferences()->get()->where('satisfied', true)->count();
        $total_preference_count = $week->preferences()->count();
        return back()->with('message',
            [
                "type" => "success",
                "body" => "Planning for week " . $request->week->name . " was successful " .
                    "(satisfied $satisfied_preference_count/$total_preference_count preferences).",
            ]
        );
    }

    /**
     *  Place a scout into the highest choice program that works out
     */
    private function put_scout_in_session(Scout $scout, Week $week) {
        $scoutAssignedToSession = false;
        foreach($scout->preferences as $preference){

            //If satisfied, skip preference
            if($preference->satisfied) {
                Log::debug("This scout's preference was already satisfied");
                continue;
            }

            //Check eligibility for scout. If not eligible, skip preference
            if($scout->age < $preference->program->min_scout_age){
                Log::debug($scout->first_name . " " . $scout->last_name . " is not old enough (need " . $preference->program->min_scout_age . ", got " . $scout->age . ")");
                continue;
            }

            $sessions = Session::where('program_id', $preference->program_id)
                ->withCount('scouts')->orderByDesc('scouts_count') // Starting with the session that's closest to full
                ->where('week_id', $week->id)
                ->get()->where('full', false); // Ignore full sessions;
            foreach ($sessions as $session) {
                
                /* if ($scout->subcamp != $session->subcamp)//Ignore different subcamp slots
                    continue;
 */
                $scout_has_conflict = false;
                $conflict = null;
                foreach ($scout->sessions as $potentialConflict) {
                    Log::debug("About to check for overlaps...");
                    if ($potentialConflict->overlaps($session)) {
                        $scout_has_conflict = true; 
                        $conflict = $potentialConflict;
                    }
                }
                if ($scout_has_conflict) {
                    Log::debug("The scout has a conflict (" . $conflict->program->name . ")");
                    continue; // try the next session time
                }

                // Assign scout to session
                $session->scouts()->attach($scout->id);
                $scout->refresh(); // Invalidate the cache
                $scoutAssignedToSession = true;
                break;
            }
        }

        return $scoutAssignedToSession; // Did we change anything for this scout?
    }

    public function clearSessions() {
        foreach(\App\Models\Scout::all() as $scout) {
            $scout->sessions()->detach();
        }
    }

    public function dropSessions($session, $scout){
        $session->scouts()->detatch($scout->id);
        //confirmation message
    }

    public function getStats(Request $request) {
        $p = Preference::all();
        return view('stats')
            ->with('subcamps', $p->groupBy(function($item, $key) {
                return $item->scout->subcamp;
            }))
            ;
    }

}
