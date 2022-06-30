<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ParticipationRequirement extends Model
{
    use HasFactory;

    /**
    * The scouts that satisfy this participation requirement.
    */
    public function scouts()
    {
        return $this->belongsToMany(Scout::class);
    }

    /**
    * The programs that require this participation requirement.
    */
    public function programs()
    {
        return $this->belongsToMany(Program::class);
    }
}
