<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RabItem extends Model
{
    protected $table = 'rab_items';

    protected $fillable = [
        'rab_id',
        'uraian',
        'volume',
        'satuan',
        'harga_satuan',
        'jumlah',
        'potongan_50_persen',
        'pajak',
        'jumlah_pasca_pajak',
        'surat_undangan',
        'kak',
        'materi',
        'notulen',
        'absen',
        'kuitansi',
        'keterangan_item',
        'surat_tugas',
        'laporan',
        'ktp',
        'npwp',
    ];

    protected $casts = [
        'volume' => 'decimal:2',
        'harga_satuan' => 'decimal:2',
        'jumlah' => 'decimal:2',
        'potongan_50_persen' => 'decimal:2',
        'pajak' => 'decimal:2',
        'jumlah_pasca_pajak' => 'decimal:2',
        'surat_undangan' => 'boolean',
        'kak' => 'boolean',
        'materi' => 'boolean',
        'notulen' => 'boolean',
        'absen' => 'boolean',
        'kuitansi' => 'boolean',
        'surat_tugas' => 'boolean',
        'laporan' => 'boolean',
        'ktp' => 'boolean',
        'npwp' => 'boolean',
    ];

    public function rab()
    {
        return $this->belongsTo(Rab::class);
    }
}
