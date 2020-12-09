<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'room_id',
        'tamagotchi_id'
    ];

    public function hotelRoom(){
        return $this->hasOne('App\Models\HotelRoom');
    }
}
