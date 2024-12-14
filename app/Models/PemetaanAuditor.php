<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemetaanAuditor extends Model
{
    use HasFactory;

    protected $table = 'pemetaan_auditor';
    protected $fillable = ['id_unit_kerja', 'id_auditor'];

    public function unitKerja()
    {
        return $this->belongsTo(User::class, 'id_unit_kerja');
    }

    public function auditor()
    {
        return $this->belongsTo(User::class, 'id_auditor');
    }
}
