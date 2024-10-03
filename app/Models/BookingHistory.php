<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;

class BookingHistory extends Model
{
    use HasFactory;

    // BookingHistoryとBooking: 各予約履歴は一つの予約に属する。
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
