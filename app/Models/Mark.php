<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Mark extends Model
{
    protected $fillable = ['examination_id', 'candidate_id', 'subject_id', 'school_id', 'mark', 'status', 'entered_by', 'verified_by', 'entered_at', 'verified_at', 'rejection_reason'];

    protected function casts(): array
    {
        return [
            'mark' => 'decimal:2',
            'entered_at' => 'datetime',
            'verified_at' => 'datetime',
        ];
    }

    public function examination()
    {
        return $this->belongsTo(Examination::class);
    }

    public function candidate()
    {
        return $this->belongsTo(Candidate::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }

    public function enteredBy()
    {
        return $this->belongsTo(User::class, 'entered_by');
    }

    public function verifiedBy()
    {
        return $this->belongsTo(User::class, 'verified_by');
    }
}
