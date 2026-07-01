<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Agenda extends Model
{
    use HasFactory;
    
    protected $table = "agenda";
    protected $primaryKey = "id_agenda";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'nama_agenda',
        'id_penyelenggara',
        'lokasi',
        'waktu_mulai',
        'waktu_berakhir',
        'status'
    ];

    public function casts() {
        return [
            'waktu_mulai' => 'datetime',
            'waktu_berakhir' => 'datetime'
        ];
    }

    public function penyelenggara() {
        return $this->belongsTo(User::class, 'id_penyelenggara', 'id');
    }

    public function participants() {
        return $this->belongsToMany(User::class, 
                                    Partisipan::class, 
                                    'id_agenda', 
                                    'id_user', 
                                    'id_agenda', 
                                    'id'
                                    );
    }
}
