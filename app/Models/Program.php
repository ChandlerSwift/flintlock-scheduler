<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;

    protected $guarded = [];

    public function sessions() {
        return $this->hasMany(Session::class);
    }

    public function preference() {
        return $this->hasMany(Preference::class);
    }

    public function changeRequests() {
        return $this->hasMany(ChangeRequest::class);
    }

    public function participationRequirements()
    {
        return $this->belongsToMany(ParticipationRequirement::class);
    }

    public function defaultSessions() {
        return $this->hasMany(DefaultSession::class);
    }
}
