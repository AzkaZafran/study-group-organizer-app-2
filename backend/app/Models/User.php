<?php

namespace App\Models;

use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Database\Eloquent\Model;

class User extends Model implements Authenticatable
{
    protected $table = "users";
    protected $primaryKey = "id";
    protected $keyType = "int";
    public $timestamps = true;
    public $incrementing = true;

    protected $fillable = [
        'username',
        'password',
        'email',
        'is_verified'
    ];

    public function getAuthIdentifier() {
        return $this->id;
    }

    public function getAuthPassword() {
        return $this->password;
    }

    public function getAuthIdentifierName() {
        return 'id';
    }

    public function getAuthPasswordName() {
        return 'password';
    }

    public function getRememberToken() {
        return $this->token;
    }

    public function getRememberTokenName() {
        return 'token';
    }

    public function setRememberToken($value) {
        $this->token = $value;
    }
}
