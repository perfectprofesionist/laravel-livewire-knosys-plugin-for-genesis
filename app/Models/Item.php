<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class item extends Model
{
    use HasFactory;
    protected $with = [
        'tags'
    ];
    
    protected $fillable = [
        'item_id',
        'type'
    ];
    
    public function tags()
    {
        return $this->morphToMany(Tag::class, 'taggable');
    }
}
