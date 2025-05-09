<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StoreHours extends Model
{
    use HasFactory;

    protected $fillable = ['day', 'hours', 'is_every_other'];

    protected $casts = [
        'is_every_other' => 'boolean',
    ];
}
