<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PemetaanStandarAudit extends Model
{
    use HasFactory;

    protected $table = 'pemetaan_standar_audit';
    protected $fillable = ['id_standar', 'id_user'];

    public function standarAudit()
    {
        return $this->belongsTo(StandarAudit::class, 'id_standar');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id_user');
    }
}
