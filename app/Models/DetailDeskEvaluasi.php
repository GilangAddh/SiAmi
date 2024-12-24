<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DetailDeskEvaluasi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'detail_desk_evaluasi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'id_desk',
        'id_pernyataan',
        'pernyataan',
        'indikator',
        'pertanyaan',
        'auditee',
        'bukti_objektif',
        'bukti_evaluasi',
        'evaluasi_diri',
        'pertanyaan_tambahan',
        'kategori_temuan',
        'hasil_audit',
        'check_auditor',
        'latest_update_time_auditee',
        'latest_update_by_auditee',
        'latest_update_time_auditor',
        'latest_update_by_auditor',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'indikator' => 'array',
        'pertanyaan' => 'array',
        'auditee' => 'array',
        'bukti_objektif' => 'array',
        'bukti_evaluasi' => 'array',
        'pertanyaan_tambahan' => 'array',
        'check_auditor' => 'array',
        'latest_update_time_auditee' => 'datetime',
        'latest_update_time_auditor' => 'datetime',
    ];

    /**
     * Get the desk evaluasi associated with the detail.
     */
    public function deskEvaluasi()
    {
        return $this->belongsTo(DeskEvaluasi::class, 'id_desk');
    }

    /**
     * Get the pernyataan associated with the detail.
     */
    public function pernyataan()
    {
        return $this->belongsTo(PernyataanStandar::class, 'id_pernyataan');
    }
}
