<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    protected $fillable = [
        'judul',
        'deskripsi',
        'tanggal_mulai',
        'tanggal_akhir',
        'status',
    ];

    protected $casts = [
        'tanggal_mulai' => 'date',
        'tanggal_akhir' => 'date',
    ];

    public function documents()
    {
        return $this->hasMany(Document::class);
    }

    public function rabs()
    {
        return $this->hasMany(Rab::class);
    }

    public function photos()
    {
        return $this->hasMany(Photo::class);
    }

    // Get documents grouped by jenis_dokumen with user count
    public function getDocumentStats()
    {
        $stats = [];
        $jenisDokumen = ['RKAKL', 'RAPK', 'SPJ', 'LKJ', 'LAKIP', 'RAB'];
        
        foreach ($jenisDokumen as $jenis) {
            if ($jenis === 'RAB') {
                // Count unique users who uploaded RAB to this agenda
                $uploaded = $this->rabs()
                    ->distinct('user_id')
                    ->count('user_id');
            } else {
                // Count unique users who uploaded document to this agenda
                $uploaded = $this->documents()
                    ->where('jenis_dokumen', $jenis)
                    ->distinct('user_id')
                    ->count('user_id');
            }
            
            $stats[$jenis] = [
                'uploaded' => $uploaded,
                'total' => \App\Models\User::where('role', 'karyawan')->count(),
            ];
        }
        
        return $stats;
    }

    // Get percentage for a specific jenis_dokumen
    public function getPercentage($jenisDokumen)
    {
        $stats = $this->getDocumentStats();
        if (isset($stats[$jenisDokumen])) {
            $total = $stats[$jenisDokumen]['total'];
            $uploaded = $stats[$jenisDokumen]['uploaded'];
            return $total > 0 ? round(($uploaded / $total) * 100, 2) : 0;
        }
        return 0;
    }
}
