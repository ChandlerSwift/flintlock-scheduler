<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use PhpOffice\PhpSpreadsheet\IOFactory;
use App\Models\Scout;
use App\Models\Preference;

class AdminController extends Controller
{

    public function import_data() {
        $inputFileName = __DIR__ . '/sampleData/example1.xls';//todo
        $spreadsheet = IOFactory::load($inputFileName);
        $sheetData = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        $sheetData = array_slice($sheetData, 3);
        foreach($sheetData as $row){
            if($scoutExists)
            
            //create preference 
            preg_match('/(.*) \((\d*)(?:st|nd|rd|th) Pref\)/', $row['A'],  $regex_result);
            $preference->name = $regex_result[1];
            $preference->rank = $regex_result[2];
        }
        var_dump($sheetData);
    }

    /* plan_week */
    public function plan_week() {
        $scouts = \App\Models\Scout::order_by_desc('age', 'rank');
        while($still_filling){
            foreach($scouts as $scouts) {
                // Try to insert scout into first preference
                // if that's full, try second, and so on.
                // Once we find one that works, mark that preference as satisfied
                //    and continue on to the next scout
            }
        } // TODO: end this somehow.
    }
}
