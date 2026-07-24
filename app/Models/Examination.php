<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Examination extends Model
{
    protected $fillable = ['name', 'academic_year_id', 'exam_type_id', 'region_id', 'start_date', 'end_date', 'status', 'created_by', 'description'];

    protected function casts(): array
    {
        return [
            'start_date' => 'date',
            'end_date' => 'date',
        ];
    }

    public function academicYear()
    {
        return $this->belongsTo(AcademicYear::class);
    }

    public function examType()
    {
        return $this->belongsTo(ExamType::class);
    }

    public function region()
    {
        return $this->belongsTo(Region::class);
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function districts()
    {
        return $this->belongsToMany(District::class, 'examination_district');
    }

    public function schools()
    {
        return $this->belongsToMany(School::class, 'examination_school');
    }

    public function subjects()
    {
        return $this->belongsToMany(Subject::class, 'examination_subject');
    }

    public function classes()
    {
        return $this->belongsToMany(Classes::class, 'examination_class', 'examination_id', 'class_id');
    }

    public function candidates()
    {
        return $this->hasMany(Candidate::class);
    }

    public function panels()
    {
        return $this->hasMany(Panel::class);
    }

    public function marks()
    {
        return $this->hasMany(Mark::class);
    }
}
