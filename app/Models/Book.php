<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
    use HasFactory;

    protected $fillable = [
      'name', 'description', 'img', 'publication'
    ];

    public function editions(){
        return $this->hasMany(Edition::class);
    }
}
