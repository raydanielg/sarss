<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ExamType extends Model
{
    protected $fillable = ['name', 'code', 'description'];

    public function examinations()
    {
        return $this->hasMany(Examination::class);
    }
}
