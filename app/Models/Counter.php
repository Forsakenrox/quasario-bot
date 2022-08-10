<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    protected $guarded = [''];
    protected $casts = [
        'last_attack_at' => 'datetime',
    ];
    use HasFactory;
}
