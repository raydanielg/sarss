<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Assignment extends Model
{
    protected $fillable = ['panel_id', 'user_id', 'district_id', 'examination_id', 'subject_id'];

    public function panel()
    {
        return $this->belongsTo(Panel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function examination()
    {
        return $this->belongsTo(Examination::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function schools()
    {
        return $this->belongsToMany(School::class, 'assignment_school');
    }
}
