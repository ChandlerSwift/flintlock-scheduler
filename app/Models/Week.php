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
}
