<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PenugasanAudit extends Model
{
    use HasFactory;

    protected $table = 'penugasan_audit';
    protected $fillable = ['id_periode', 'id_unit', 'id_auditor'];
}
