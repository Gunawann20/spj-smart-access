<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    protected $fillable = [
        'agenda_id',
        'file_path',
        'file_name',
        'keterangan',
    ];

    public function agenda()
    {
        return $this->belongsTo(Agenda::class);
    }
}
