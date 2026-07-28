<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Catatan extends Model
{
    use HasFactory;

    protected $table = 'catatan';
    protected $primaryKey = "id_catatan";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'id_author',
        'id_agenda',
        'judul_catatan',
        'catatan'
    ];

    public function viewed() {
        return $this->belongsToMany(User::class,
                                    CatatanTerbaca::class,
                                    'id_catatan',
                                    'id_user',
                                    'id_catatan',
                                    'id')
                    ->withPivot('status')
                    ->wherePivot('status', 'sudah dibaca');
    }
}
