<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PanelDataEntry extends Model
{
    protected $table = 'panel_data_entry';
    protected $fillable = ['panel_id', 'user_id'];

    public function panel()
    {
        return $this->belongsTo(Panel::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
