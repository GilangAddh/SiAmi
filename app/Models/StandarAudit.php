<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandarAudit extends Model
{
    use HasFactory;

    protected $table = 'standar_audit';
    protected $fillable = ['nama_standar', 'nomer_dokumen', 'nomer_revisi', 'tanggal_terbit', 'is_active'];

    public function indikatorStandarAudit()
    {
        return $this->hasMany(IndikatorStandarAudit::class, 'id_standar');
    }
}
