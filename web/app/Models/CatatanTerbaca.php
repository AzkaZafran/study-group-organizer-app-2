<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CatatanTerbaca extends Model
{
    protected $table = "catatan_terbaca";
    protected $primaryKey = "id_catatan_terbaca";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'id_user',
        'id_catatan',
        'status'
    ];
}
