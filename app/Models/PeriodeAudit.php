<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PeriodeAudit extends Model
{
    use HasFactory;
    protected $table = 'periode_audit';
    protected $fillable = ['tanggal_mulai', 'tanggal_akhir', 'is_active'];

    public function jadwalAudit()
    {
        return $this->hasMany(JadwalAudit::class, 'id_periode');
    }
}
