<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Catatan extends Model
{
    protected $table = 'catatan';
    protected $primaryKey = "id_catatan";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'id_author',
        'id_agenda',
        'catatan'
    ];
}
