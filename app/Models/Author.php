<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
      'name', 'surname', 'patronymic'
    ];

    public function editions(){
        return $this->hasMany(Edition::class);
    }
}
