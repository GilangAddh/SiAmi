<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IndikatorStandarAudit extends Model
{
    use HasFactory;

    protected $table = 'indikator_standar_audit';
    protected $fillable = ['nomer_pertanyaan_standar', 'pertanyaan_standar', 'indikator_pertanyaan', 'bukti_objektif', 'original_bukti_objektif', 'id_standar'];

    public function standarAudit()
    {
        return $this->belongsTo(StandarAudit::class, 'id_standar');
    }
}
