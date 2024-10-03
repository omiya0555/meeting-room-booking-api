<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use App\Models\Booking;
use App\Models\Participant;

// 追加させるのを忘れて前回のAuthControllerを使用していため、
// 500 server errorが発生していたが、HasApiTokensを追加する事で
// createTokenメソッドを使用できるようになった。
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasApiTokens;

    // UserとBooking: 1人のユーザーは複数の予約を持つことができる
    public function bookings()
    {
        return $this->hasMany(Booking::class);
    }

    // UserとParticipant: 1人のユーザーは複数の参加者情報を持つことができる
    public function participants()
    {
        return $this->hasMany(Participant::class);
    }


    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }
}
