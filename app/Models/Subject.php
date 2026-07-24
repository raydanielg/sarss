<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    protected $fillable = ['name', 'code', 'max_marks'];

    public function examinations()
    {
        return $this->belongsToMany(Examination::class, 'examination_subject');
    }

    public function panels()
    {
        return $this->hasMany(Panel::class);
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }

    public function candidates()
    {
        return $this->belongsToMany(Candidate::class, 'marks')->withPivot('mark', 'status');
    }
}
