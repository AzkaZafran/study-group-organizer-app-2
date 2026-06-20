<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partisipan extends Model
{
    protected $table = "partisipan";
    protected $primaryKey = "id_partisipan";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'id_agenda',
        'id_user',
        'status'
    ];
}
