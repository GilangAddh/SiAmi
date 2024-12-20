<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PernyataanStandar extends Model
{
    use HasFactory;

    protected $table = 'pernyataan_standar';
    protected $fillable = ['pernyataan_standar', 'indikator_pertanyaan', 'pertanyaan', 'bukti_objektif', 'original_bukti_objektif', 'id_standar', 'is_active'];

    protected $casts = [
        'pertanyaan' => 'array',
        'indikator_pertanyaan' => 'array',
        'bukti_objektif' => 'array',
        'original_bukti_objektif' => 'array'
    ];

    public function standarAudit()
    {
        return $this->belongsTo(StandarAudit::class, 'id_standar');
    }
}
