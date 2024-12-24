<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeskEvaluasi extends Model
{
    use HasFactory;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'desk_evaluasi';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'hard_periode_awal',
        'hard_periode_akhir',
        'hard_unit',
        'hard_standar',
        'hard_auditor',
        'soft_periode',
        'soft_unit',
        'soft_standar',
        'soft_auditor',
        'status',
        'tanggal_mulai_evaluasi',
        'tanggal_mulai_audit',
        'catatan',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'hard_periode_awal' => 'date',
        'hard_periode_akhir' => 'date',
        'hard_auditor' => 'array',
        'soft_auditor' => 'array',
        'tanggal_mulai_evaluasi' => 'date',
        'tanggal_mulai_audit' => 'date',
    ];
}
