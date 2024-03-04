<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    public $incrementing = false;
    protected $primaryKey = 'name';
    use HasFactory;
}
