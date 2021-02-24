<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Session extends Model
{
    use HasFactory;

    public function scouts() {
        return $this->belongsToMany(Scout::class);
    }

    public function sessions() {
        return $this->belongsTo(Program::class);
    }
}
