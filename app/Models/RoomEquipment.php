<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoomEquipment extends Model
{
    use HasFactory;

    // RoomEquipmentとRoom: 各部屋の備品は一つの会議室に属する。
    public function room()
    {
        return $this->belongsTo(Room::class);
    }
}
