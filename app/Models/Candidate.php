<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidate extends Model
{
    protected $fillable = ['examination_id', 'candidate_number', 'name', 'gender', 'school_id', 'district_id', 'class_id', 'stream_id'];

    protected function casts(): array
    {
        return ['gender' => 'string'];
    }

    public function examination()
    {
        return $this->belongsTo(Examination::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function district()
    {
        return $this->belongsTo(District::class);
    }

    public function class()
    {
        return $this->belongsTo(Classes::class, 'class_id');
    }

    public function stream()
    {
        return $this->belongsTo(Stream::class);
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }
}
