<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class School extends Model
{
    protected $fillable = ['district_id', 'name', 'code', 'registration_number'];

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function panelMarkers()
    {
        return $this->hasMany(PanelMarker::class);
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public function examinations()
    {
        return $this->belongsToMany(Examination::class, 'examination_school');
    }
}
