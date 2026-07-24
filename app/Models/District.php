<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class District extends Model
{
    protected $fillable = ['region_id', 'name', 'code'];

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function schools()
    {
        return $this->hasMany(School::class);
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
