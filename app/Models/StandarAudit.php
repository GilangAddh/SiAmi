<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandarAudit extends Model
{
    use HasFactory;

    protected $table = 'standar_audit';
    protected $fillable = ['nama_standar', 'nomer_dokumen', 'nomer_revisi', 'tanggal_terbit', 'is_active'];

    public function pernyataanStandar()
    {
        return $this->hasMany(PernyataanStandar::class, 'id_standar');
    }

    public function jadwalAudit()
    {
        return $this->hasMany(JadwalAudit::class, 'id_standar');
    }
}
