<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Partisipan extends Model
{
    use HasFactory;

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

    public function agenda() {
        return $this->belongsTo(Agenda::class, 'id_agenda', 'id_agenda');
    }
}
