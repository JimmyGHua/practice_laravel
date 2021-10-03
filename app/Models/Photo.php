<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Photo extends Model
{
    use HasFactory;

    // add in lec 67 naming called imageable for laravel convention
    public function imageable(){
        return $this->morphTo();
    }
}
