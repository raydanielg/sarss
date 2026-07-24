<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanelMarker extends Model
{
    protected $fillable = ['panel_id', 'name', 'phone', 'school_id'];

    public function panel()
    {
        return $this->belongsTo(Panel::class);
    }

    public function school()
    {
        return $this->belongsTo(School::class);
    }
}
