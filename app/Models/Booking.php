<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Room;

class Booking extends Model
{
    use HasFactory;

    // Fillableプロパティ
    protected $fillable = [
        'room_id',
        'start_time',
        'end_time',
        'status_id',
        'user_id',
        'room_name',
        'description',
        'capacity',
    ];

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
    public function participant()
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
