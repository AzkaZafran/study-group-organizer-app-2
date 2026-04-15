<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtpCodes extends Model
{
    protected $table = 'otp_codes';
    protected $primaryKey = 'id_otp_codes';
    protected $keyType = 'int';
    public $timestamps = true;
    public $incrementing = true;
    
    protected $fillable = [
        'otp_codes',
        'is_used'
    ];
}
