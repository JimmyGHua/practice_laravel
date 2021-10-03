<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory;
    use SoftDeletes;
    // 新增一個叫做deleted_at的欄位 (laravel 8好像有改只要use SoftDeletes;就會新增deleted_at欄位不用再額外註明)
    protected $dates = ['deleted_at'];

    // if the model name have different table name need to override with comment
    // 如果model名稱非為post 需自行加如下面註解那行
    // protected $table = 'posts';
    // 如果主鍵名稱不叫id 需自行加如下面註解那行
    // protected $primaryKey = 'post_id';

    // 註明這兩個欄位是可以被create給建立填滿的
    protected $fillable = [
        'title',
        'content',
    ];

    public function user(){
        // post table 內的 user_id 是 belongsTo User的id
        return $this->belongsTo('App\Models\User');
    }

    // add in lec 67 取得post所對應的photo 這個取名就可以自行取
    public function photos(){
        return $this->morphMany('App\Models\Photo','imageable');
    }
}
