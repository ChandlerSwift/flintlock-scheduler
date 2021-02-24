<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class AdminController extends Controller
{

    public function import_data() {
        
    }

    /* plan_week */
    public function plan_week() {
        $scouts = \App\Models\Scout::order_by_desc('age', 'rank');
        while $still_filling {
            foreach($scouts as $scouts) {
                // Try to insert scout into first preference
                // if that's full, try second, and so on.
                // Once we find one that works, mark that preference as satisfied
                //    and continue on to the next scout
            }
        } // TODO: end this somehow.
    }
}
