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

    public function program() {
        return $this->belongsTo(Program::class);
    }

    public function getFullAttribute() {
        return $this->scouts()->count() >= $this->program->max_participants;
    }

    public function getRunningAttribute() { //$session->running
        return $this->scouts()->count() > 0;
    }

    public function overlaps(Session $other) {
        // equivalently: return ! ($this->end_time < $other->start_time || $this->start_time > $other->end_time);
        return $this->end_time >= $other->start_time && $this->start_time <= $other->end_time;
    }
}
