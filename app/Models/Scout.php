<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Scout extends Model
{
    use HasFactory;

    protected $guarded = []; // Allow mass filling of all attributes

    public function sessions()
    {
        return $this->belongsToMany(Session::class);
    }

    public function preferences()
    {
        return $this->hasMany(Preference::class);
    }

    public function changeRequests()
    {
        return $this->hasMany(ChangeRequest::class);
    }

    public function participationRequirements()
    {
        return $this->belongsToMany(ParticipationRequirement::class);
    }

    public function satisfiedParticipationRequirements()
    {
        $prs = $this->participationRequirements;

        // This is jank; there's gotta be a better way to do this
        $satisfied_prs = [];
        foreach (ParticipationRequirement::all() as $pr) {
            array_push($satisfied_prs, [$pr, $prs->contains($pr)]);
        }

        return collect($satisfied_prs);
    }

    public function missingReqsFor(Program $program)
    {
        $reqs = [];
        foreach ($program->participationRequirements as $req) {
            if (! $this->participationRequirements->contains($req)) {
                array_push($reqs, $req);
            }
        }

        return collect($reqs);
    }

    public function needs(ParticipationRequirement $req, bool $withRequests = false)
    {
        foreach ($this->sessions as $session) {
            if ($session->program->participationRequirements->contains($req)) {
                return true;
            }
        }
        if ($withRequests) {
            foreach ($this->changeRequests as $cr) {
                if ($cr->program->participationRequirements->contains($req)) {
                    return true;
                }
            }
        }

        return false;
    }

    public function meetsReqsFor(Program $program)
    {
        return count($this->missingReqsFor($program)) == 0;
    }

    public function week()
    {
        return $this->belongsTo(Week::class);
    }

    public function getSubcampAbbrAttribute()
    {
        if ($this->subcamp == 'Buckskin') {
            return 'B';
        } elseif ($this->subcamp == 'Ten Chiefs') {
            return 'TC';
        } elseif ($this->subcamp == 'Voyageur') {
            return 'V';
        } elseif ($this->subcamp == 'UNKNOWN') {
            return '?';
        } else {
            throw new \Exception('Unknown subcamp '.$this->subcamp);
        }
    }
}
