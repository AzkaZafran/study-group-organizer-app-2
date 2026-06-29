<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UndanganAgenda extends Model
{
    protected $table = "undangan_agenda";
    protected $primaryKey = "id_invite";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'id_agenda',
        'invite_code',
        'expired_at'
    ];

    public function agenda() {
        return $this->belongsTo(Agenda::class, 'id_agenda', 'id_agenda');
    }
}
