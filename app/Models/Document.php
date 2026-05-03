<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Document extends Model
{
    protected $fillable = [
        'user_id',
        'agenda_id',
        'nama_dokumen',
        'jenis_dokumen',
        'tahun',
        'pelaksana',
        'kode_ro',
        'jumlah_anggaran',
        'nama_verifikator',
        'tanggal_sp2d',
        'jumlah_anggaran_sp2d',
        'file_path',
        'file_type',
        'ukuran_file',
        'status',
        'keterangan',
        'admin_id',
        'rab_id',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function folder()
    {
        return $this->belongsTo(Folder::class);
    }

    public function agenda()
    {
        return $this->belongsTo(Agenda::class);
    }

    public function rab()
    {
        return $this->belongsTo(Rab::class);
    }

    public function associatedRab()
    {
        // First try via relationship
        if ($this->rab_id) {
            return $this->rab;
        }

        // Fallback for older records
        if ($this->jenis_dokumen !== 'rab') {
            return null;
        }

        $nomorRab = null;
        if (preg_match('/RAB-\d{4}-\d{3}/', $this->keterangan, $matches)) {
            $nomorRab = $matches[0];
        } elseif (preg_match('/RAB-\d{4}-\d{3}/', $this->file_path, $matches)) {
            $nomorRab = $matches[0];
        }

        if ($nomorRab) {
            return Rab::where('nomor_rab', $nomorRab)->first();
        }

        return null;
    }
}
