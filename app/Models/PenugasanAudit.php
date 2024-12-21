<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenugasanAudit extends Model
{
    use HasFactory;

    protected $table = 'penugasan_audit';
    protected $fillable = ['id_jadwal', 'id_auditor'];

    public function jadwalAudit()
    {
        return $this->belongsTo(JadwalAudit::class, 'id_jadwal');
    }

    public function auditor()
    {
        return $this->belongsTo(User::class, 'id_auditor');
    }
}
