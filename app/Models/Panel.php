<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Panel extends Model
{
    protected $fillable = ['examination_id', 'subject_id', 'moderator_user_id'];

    public function examination()
    {
        return $this->belongsTo(Examination::class);
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class);
    }

    public function moderator()
    {
        return $this->belongsTo(User::class, 'moderator_user_id');
    }

    public function markers()
    {
        return $this->hasMany(PanelMarker::class);
    }

    public function dataEntries()
    {
        return $this->hasMany(PanelDataEntry::class);
    }

    public function assignments()
    {
        return $this->hasMany(Assignment::class);
    }
}
