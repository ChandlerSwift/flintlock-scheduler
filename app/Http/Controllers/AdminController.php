<?php

namespace App\Http\Controllers;

use App\Models\ChangeRequest;
use Illuminate\Http\Request;
use App\Models\Scout;
use App\Models\Preference;
use App\Models\Program;
use App\Models\Session;
use App\Models\Week;
use Illuminate\Support\Facades\Log;

class AdminController extends Controller
{
    public function import_data(Request $request) {
        if (!$request->hasfile('csv')) {
            return back()->with('message',
                ["type" => "warning", "body" => "No CSV file selected."]
            );
        }

        $week = Week::find($request->week_id);
        $scouts_added = 0;
        $preferences_added = 0;
        foreach($request->file('csv') as $key => $file) {
            if (($handle = fopen($file->path(), "r")) === FALSE) {
                throw new \Exception("Unable to open file");
            }
            $headers = fgetcsv($handle);

            $row = 1;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE) {
                // Create a dict; that's easier to work with than having to do an
                // index lookup every time we want to access a field later on!
                $record = [];
                for ($i = 0; $i < count($headers); $i++) {
                    $record[$headers[$i]] = $data[$i];
                }
                try {
                    // if ($record['Subtitle'] != $week->name ) {
                    //     throw new \Exception("Trying to add scout from " . $record['Subtitle'] . ' to week ' . $week->name);
                    // }
                    $pre_existing_scout = Scout::where('first_name', $record['First Name'])
                        ->where('last_name', $record['Last Name'])
                        ->where('unit', $record['Unit Number'])
                        ->where('council', $record['Council'])
                        ->where('week_id', $request->week_id)
                        ->first();
                    if ($pre_existing_scout) {
                        Log::info("Found duplicate scout for " . $record['First Name'] . ' ' . $record['Last Name'] . '; skipping.');
                        continue;
                    }
                    $scout = new Scout;
                    $scout->week_id = $request->week_id;
                    $scout->first_name = $record['First Name'];
                    $scout->last_name = $record['Last Name'];

                    if (array_key_exists('Scouting Rank', $record)) { // Apparently All Star doesn't have this?
                        $ranks = ['Scout', 'Tenderfoot', 'Second Class', 'First Class', 'Star', 'Life', 'Eagle'];
                        $rank = array_search($record['Scouting Rank'], $ranks);
                        if ($rank === false) { // comparing strictly, as opposed to the falsy rank Scout (0)
                            $rank = 0;
                            Log::info("Unknown rank for scout " . $record['First Name'] . ' ' . $record['Last Name'] . ", unit " . $record['Unit Number'] . " (setting to Scout)");
                        }
                    } else {
                        Log::warning("No 'Scouting Rank' column in input data; setting to Scout");
                        $rank = 0;
                    }
                    $scout->rank = $rank;

                    $scout->age = $record['Age'] ?: 10;
                    $scout->council = $record['Council'];
                    $scout->gender = $record['Gender'];
                    if (str_ends_with($record['Title'], "All Star")) {
                        $scout->subcamp = "Buckskin";
                        $scout->unit = "1910";
                        $scout->site = "All Star";
                    } else {
                        $scout->subcamp = explode(": ", $record['Title'])[1];
                        $scout->unit = $record['Unit Number'];
                        $scout->site = "UNKNOWN";
                    }
                    $scout->save();
                    $scouts_added++;

                    for ($i = 1; $i <= 4; $i++) {
                        $program = Program::where('name', $record["Flintlock Tier 1, Preference $i"])->first();
                        if (!$program) {
                            Log::warning("trying to find nonexistent program " . $record["Flintlock Tier 1, Preference $i"]);
                        } else {
                            $preference = new Preference;
                            $preference->program_id = $program->id;
                            $preference->rank = $i;
                            $preference->scout_id = $scout->id;
                            $preference->save();
                            $preferences_added++;
                        }
                    }
                } catch (\Exception $e) {
                    fclose($handle);
                    throw new \Exception(
                        "Error processing row " . $row . ", scout " .
                        $record['First Name'] . ' ' . $record['Last Name'] . ' (unit ' .
                        $record['Unit Number'] . '): ' . $e->getMessage()
                    );
                }
                $row++;
            }
            fclose($handle);
        }
        return back()->with('message',
            ["type" => "success", "body" => "Data import for week " . Week::find($request->week_id)->name . " was successful: imported $scouts_added scouts and $preferences_added preferences."]
        );
    }

    public function import_event_data(Request $request) {
        if (!$request->hasFile('csv')) {
            return back()->with('message',
                ["type" => "warning", "body" => "No csv selected."]
            );
        }

        foreach($request->file('csv') as $key => $file) {
            if (($handle = fopen($file->path(), "r")) === FALSE) {
                throw new \Exception("Unable to open file");
            }
            $headers = fgetcsv($handle);

            $row = 1;
            while (($data = fgetcsv($handle, null, ",")) !== FALSE) {
                // Create a dict; that's easier to work with than having to do an
                // index lookup every time we want to access a field later on!
                $record = [];
                for ($i = 0; $i < count($headers); $i++) {
                    $record[$headers[$i]] = $data[$i];
                }
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
        }
        return back()->with('message',
            [
                "type" => "success",
                "body" => "Import successful.",
            ]
        );
    }

    public function plan_week(Request $request, $week) {
        $week = Week::find($week);
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

    public function getStats() {
        return view('stats')
            ->with('programs', Program::all()->filter(function($program, $index) {
                return $program->sessions()->where('every_day', false)->count() > 0;
            }))
            ->with('preferences', Preference::all())
            ->with('changeRequests', ChangeRequest::all())
            ->with('sessions', Session::all());
    }

    public function assign_sites() {
        // I think I could do all this nonsense with a single well-written SQL
        // query, but I don't want to think that hard this morning.
        // TODO: rewrite?
        $weeks = [];
        foreach (\App\Models\Week::all() as $week) {
            $subcamp_scouts = $week->scouts()->where('site', 'UNKNOWN')->get();
            if ($subcamp_scouts->count() > 0) {
                $subcamps = [];
                foreach($subcamp_scouts->groupBy('subcamp') as $subcamp => $scouts) {
                    if ($scouts->count() > 0) {
                        $units = [];
                        foreach ($scouts as $scout) {
                            $units[$scout->unit . $scout->council] = [
                                "unit" => $scout->unit,
                                "council" => $scout->council,
                            ];
                        }
                        array_push($subcamps, [
                            'subcamp' => $subcamp,
                            'units' => array_values($units),
                        ]);
                    }
                }
                array_push($weeks, [
                    'week' => $week,
                    'subcamps' => $subcamps,
                ]);
            }
        }
        return view('admin.assign_sites')->with('weeks_missing_sites', $weeks);
    }

    public function save_site_assignments(Request $request) {
        foreach ($request->except(['_token', 'week']) as $unitCouncil => $site) { // shouldn't excluding _token be automatic?
            $assoc = explode('-', $unitCouncil, 2);
            $unit = $assoc[0];
            $council = str_replace("_", " ", $assoc[1]); // HACK: I don't understand why I have to do this, but otherwise $council is e.g. `Northern_Star_Scouting`
            foreach (Scout::where('unit', $unit)->where('council', $council)->get() as $scout) {
                $scout->site = $site;
                $scout->save();
            }
        }
        return back();
    }

}
