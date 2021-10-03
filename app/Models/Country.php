<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Country extends Model
{
    use HasFactory;

    public function posts(){
        // 第一個參數是欲拿到之目標table(Post)
        // 第二個參數是Country_id <=> User <=> Post 透過User Table去取得Post
        // 第三個參數若使用非laravel命名時才需要 這個參數是指說在User Table內的哪個欄位記著country_id
        // 第四個參數若使用非laravel命名時才需要 這個參數是指說在Post Table內哪個欄位記著user_id
        // return $this->hasManyThrough('App\Models\Post','App\Models\User','the_country_id','the_user_id');

        // 根據上面所以 add in lec 65
        return $this->hasManyThrough('App\Models\Post','App\Models\User');
    }
}
