<?php

namespace App\Livewire\Forms;

use Livewire\Attributes\Validate;
use Livewire\Form;

class StandarAuditForm extends Form
{
    #[Validate('required|min:5')]
    public $nama_audit = '';

    #[Validate('required|min:5')]
    public $nomer_sertifikat = '';

    #[Validate('required|min:5')]
    public $nomer_revisi = '';

    #[Validate('required|date')]
    public $tanggal_terbit = '';
}
