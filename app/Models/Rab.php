<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Rab extends Model
{
    protected $fillable = [
        'User_id',
        'Agenda_id',
        'Judul_RAB',
        'Nomor_RAB',
        'Tanggal_RAB',
        'Waktu_Mulai',
        'Waktu_Selesai',
        'Tempat_Pelaksanaan',
        'Sumber_Kegiatan',
        'Jenis_Kegiatan',
        'Akun_yang_Digunakan',
        'Tahun_Anggaran',
        'Keterangan_RAB',
        'Total_Jumlah',
        'Nama_Pemohon',
        'Nama_Direktur',
        'Nama_Pejabat',
        'Status',
    ];

    protected $casts = [
        'Tanggal_RAB' => 'datetime',
        'Total_Jumlah' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function agenda()
    {
        return $this->belongsTo(Agenda::class);
    }

    public function items()
    {
        return $this->hasMany(RabItem::class);
    }
}
