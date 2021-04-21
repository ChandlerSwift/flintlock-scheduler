<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Scout;
use App\Models\Preference;
use App\Models\Program;
use App\Models\Session;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{

    public function import_data() {
        $inputFileName = '/home/isaac/importData.xlsx'; // TODO
        $spreadsheet = IOFactory::load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        foreach($sheetData as $row){
            if ($row['C'] == null) { // Name should never be empty, so this is an empty row. Skip it.
                continue;
            }

            if (Scout::where('first_name', $row['C'])->where('last_name', $row['D'])->where('unit', $row['E'])->first()) {
                throw new \Exception("duplicate scout found: " . $row['C'] . " " . $row['D'] . ", troop " . $row['E']);
            }
            $scout = new Scout;
            $scout->first_name = $row['C'];
            $scout->last_name = $row['D'];
            if ($row['G'] != null)
                if ($row['G'] == 'Scout')
                    $scout->rank = 0;
                else if ($row['G'] == 'Tenderfoot')
                    $scout->rank = 1;
                else if ($row['G'] == 'Second Class')
                    $scout->rank = 0;
                else if ($row['G'] == 'First Class')
                    $scout->rank = 2;
                else if ($row['G'] == 'Star')
                    $scout->rank = 3;
                else if ($row['G'] == 'Life')
                    $scout->rank = 4;
                else if ($row['G'] == 'Eagle')
                    $scout->rank = 5;
                else
                    Log::warning("Unknown rank for scout " . $row['C'] . " " . $row['D'] . ", troop " . $row['E'] . " (setting to Scout)");
            if ($row['H'] != null)
                $scout->age = $row['H'];
            $scout->unit = $row['E'];
            $scout->subcamp = $row['A'];
            $scout->site = $row['J'];
            $scout->gender = $row['F'];
            $scout->save();
            
            $row_indices = ["K", "L", "M", "N", "O", "P", "Q"];
            for ($i = 0; $i < 7; $i++) {
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
    }

    /* plan_week */
    public function plan_week() {
        $scouts = Scout::orderByDesc('age', 'rank')->get();
        $still_filling = true;
        echo "Adding scouts to session...";
        $i=0;
        while($still_filling){
            echo $i++;
            $still_filling = false;
            foreach($scouts as $scout) {
                if ($this->put_scout_in_session($scout)) {
                    $still_filling = true;
                }
            }
        }
    }

    /**
     *  Place a scout into the highest choice program that works out
     */
    private function put_scout_in_session(Scout $scout) {
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
                ->get()->where('full', false); // Ignore full sessions;
            foreach ($sessions as $session) {
                // check if scout has conflicts
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
}
