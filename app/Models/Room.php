<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;
use App\Models\BookingEquipment;

class Room extends Model
{
    use HasFactory;

    // RoomとRoomEquipment: 1つの部屋は複数の備品を持つことができる
    public function roomEquipments()
    {
        return $this->hasMany(RoomEquipment::class);
    }

    // RoomとBooking: 1つの部屋は複数の予約を持つことができる
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
