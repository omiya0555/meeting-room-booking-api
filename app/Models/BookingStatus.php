<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingStatus extends Model
{
    use HasFactory;

    // BookingStatusとBooking: 各予約状況は複数の予約情報を持つことができる。
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }
}
