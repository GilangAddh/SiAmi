<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StandarAudit extends Model
{
    use HasFactory;

    protected $table = 'standar_audit';
    protected $fillable = ['nama_standar', 'nomer_dokumen', 'nomor_revisi', 'tanggal_terbit'];
}
