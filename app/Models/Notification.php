<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class Notification extends Model
{
    use HasFactory;

    // 各通知は一人のユーザーに属する。
    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
