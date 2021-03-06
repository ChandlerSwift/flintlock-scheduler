<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    public function sessions() {
        return $this->hasMany(Session::class);
    }

    public function preference() {
        return $this->hasMany(Preference::class);
    }

}
