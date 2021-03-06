<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Preference extends Model
{
    use HasFactory;

    public function scout() {
        return $this->belongsTo(Scout::class);
    }

    public function program() {
        return $this->belongsTo(Program::class);
    }
}
