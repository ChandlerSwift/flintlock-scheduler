<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class Session extends Model
{
    use HasFactory;

    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

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
    public function changeRequests() {
        return $this->hasMany(ChangeRequest::class);
    }

    public function overlaps(Session $other) {
        Log::debug("Checking for overlap...");
        // equivalently: return ! ($this->end_time < $other->start_time || $this->start_time > $other->end_time);
        $overlaps = $this->end_time >= $other->start_time && $this->start_time <= $other->end_time;

        if ($overlaps) {
            Log::debug("Overlap found!");
        }
        return $overlaps;
    }

    public function week() {
        return $this->belongsTo(Week::class);
    }
}
