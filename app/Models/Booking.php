<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    // BookingとUser: 各予約は1人の申請者に属する（belongsTo）
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id'); // 申請者
    }

    // BookingとRoom: 各予約は１つの部屋に属する。
    public function room()
    {
        return $this->belongsTo(Room::class);
    }

    // BookingとParticipant: 各予約は複数の参加者を持つことができる
    public function participants()
    {
        return $this->hasMany(Participant::class);
    }

    // BookingとBookingStatus: 各予約は1つのステータスに属する
    public function status()
    {
        return $this->belongsTo(BookingStatus::class);
    }

    // BookingとBookingHistory: 各予約は複数の履歴を持つことができる
    public function histories()
    {
        return $this->hasMany(BookingHistory::class);
    }
}
