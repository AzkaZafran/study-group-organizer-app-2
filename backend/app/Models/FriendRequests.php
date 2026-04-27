<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FriendRequests extends Model
{
    use HasFactory;

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

    public function userPengirim() {
        return $this->belongsTo(User::class, 'id_pengirim', 'id');
    }

    public function userPenerima() {
        return $this->belongsTo(User::class, 'id_penerima', 'id');
    }
}
