<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Classes extends Model
{
    protected $fillable = ['name', 'code', 'level'];

    public function candidates()
    {
        return $this->hasMany(Candidate::class, 'class_id');
    }

    public function examinations()
    {
        return $this->belongsToMany(Examination::class, 'examination_class', 'class_id', 'examination_id');
    }
}
