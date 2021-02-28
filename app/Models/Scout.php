<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scout extends Model
{
    use HasFactory;

    protected $guarded = []; // Allow mass filling of all attributes

    public function sessions() {
        return $this->belongsToMany(Session::class);
    }

    public function preferences() {
        return $this->hasMany(Preference::class);
    }

    public function isNotElligible($min_scout_age) {
        
        if($min_scout_age > $this->age){
            return true;
        }else{
            return false;
        }
    }
    

    
}
