<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use App\Models\Booking;

class Participant extends Model
{
    use HasFactory;

    // Fillableプロパティ
    protected $fillable = [
        'booking_id',
        'user_id',
    ];

    // 各参加者は一つの予約に属する。
    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    // 各参加者は一人のユーザーに属する。
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
