<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function post(){
        // 參數內的App\Models\Post 是根據其Post的namespace
        // 若兩個table連結的關係非預設需要自己在多參數如
        // $this->hasOne('App\Models\Post','the_user_id','post_id');
        // 第二個參數若post table中的欄位不是預設的user_id 第三個參數若post table的主鍵不為id
        // user的id 這個欄位在 Post table中 hasOne 為 user_id
        return $this->hasOne('App\Models\Post');
        // 如果寫成這樣
        // return $this->hasOne('App\Models\Post')->get();
        // 這會把result serialized to JSON
        // 而如果是像沒註解寫的那樣 return 會回傳 Eloquent Builder object
        // 也就導致如果使用get()的話，前面route取道結果的地方需要用()
        // 而如果是沒有get()的話 取用這個post就不用加() (就是回傳Post這個物件)
        // 違反上面的話會報錯 ::setContent() must be of the type string or null, object given,
    }

    public function posts(){
        return $this->hasMany('App\Models\Post');
    }

    // get many to many relationship to role (user<=>role)
    public function roles(){
        // write in lec 62
        // return $this->belongsToMany('App\Models\Role');
        // write in lec 63 (讓多對多role_user表可以顯示其他欄位資訊)
        return $this->belongsToMany('App\Models\Role')->withPivot('created_at');
        // Customize tables name and columns follow the format below
        // 如果有不同的多對多表(也就是說user<=>role的多對多表不為預設之rule_user時)
        // 第二個參數若該表名稱為user_roles 第三個參數若User表的主鍵叫user_id
        // 第四個參數若Role表的主鍵叫role_id
        // return $this->belongsToMany('App\Models\Role','user_roles','user_id','role_id');
    }

    // add in lec 67 取得user所對應的photo 這個取名就可以自行取
    public function photos(){
        return $this->morphMany('App\Models\Photo','imageable');
    }
}
