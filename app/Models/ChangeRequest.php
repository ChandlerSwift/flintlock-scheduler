<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ChangeRequest extends Model
{
    use HasFactory;

    public function scout() {
        return $this->belongsTo(Scout::class);
    }

    public function program() {
        return $this->belongsTo(Program::class);
    }

    public function session() {
        return $this->belongsTo(Session::class);
    }
}
