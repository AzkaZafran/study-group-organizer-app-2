<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class FriendRequests extends Model
{
    protected $table = "friend_requests";
    protected $primaryKey = "id_request";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'id_pengirim',
        'id_penerima',
        'status'
    ];
}
