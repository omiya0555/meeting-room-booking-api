<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Booking;

class CalendarEvent extends Model
{
    use HasFactory;

    // Fillableプロパティ
    protected $fillable = [
        'booking_id',
        'event_title',
        'event_start',
        'event_end',
        'room_name',
        'description',
        'capaciry'
    ];

    // 各カレンダーイベントは一つの予約に属する。
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // BookingとRoom: 各予約は１つの部屋に属する。
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
