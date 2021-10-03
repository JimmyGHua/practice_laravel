<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    use HasFactory;

    public function users(){
        // add in lec 63
        return $this->belongsToMany('App\Models\User');
    }
}
