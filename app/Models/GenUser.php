<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GenUser extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'user_id',
        'auth_code',
        'access_token',
        'expires_in',
        'refresh_token',
        'name',
        'email',
        'role'
    ];
    
    protected $keyType = 'string';
    protected $primaryKey = 'user_id';
    
    public function conversations(){
        return $this->hasMany(Conversation::class);
    }
}
