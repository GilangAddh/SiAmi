<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JadwalAudit extends Model
{
    use HasFactory;

    protected $table = 'jadwal_audit';
    protected $fillable = ['id_periode', 'id_unit', 'id_standar', 'id_pernyataan'];

    public function periodeAudit()
    {
        return $this->belongsTo(PeriodeAudit::class, 'id_periode');
    }

    public function unitKerja()
    {
        return $this->belongsTo(User::class, 'id_unit');
    }

    public function standarAudit()
    {
        return $this->belongsTo(StandarAudit::class, 'id_standar');
    }

    public function pernyataanStandar()
    {
        return $this->belongsTo(PernyataanStandar::class, 'id_pernyataan');
    }
}
