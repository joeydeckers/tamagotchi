<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tamagotchi extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'age',
        'coins',
        'health',
        'boredom',
        'dead',
        'owner_id'
    ];
}
