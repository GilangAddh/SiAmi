<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Navigation extends Model
{
    use HasFactory;

    protected $table = 'navigation';
    protected $fillable = ['menu', 'type', 'url', 'icon', 'roles'];

    protected $casts = [
        'roles' => 'array',
    ];
}
