<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Approval extends Model
{
    protected $fillable = [
        'folder_id',
        'admin_id',
        'status',
        'keterangan',
    ];

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }
}
