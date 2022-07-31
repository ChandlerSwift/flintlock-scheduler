<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Week extends Model
{
    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'start_date' => 'datetime',
    ];

    protected $guarded = [];

    public function scouts() {
        return $this->hasMany(Scout::class);
    }

    public function sessions() {
        return $this->hasMany(Session::class);
    }

    public function changeRequests() {
        return $this->hasManyThrough(ChangeRequest::class, Scout::class);
    }

    public function preferences() {
        return $this->hasManyThrough(Preference::class, Scout::class);
    }

    public function units() {
        return $this->scouts()->select('unit', 'council')->distinct()->get();
    }
}
