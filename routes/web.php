<?php

// use App\Http\Controllers\PostsController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('welcome');
});

Route::get('/about', function () {
    return "Hi about page";
});

Route::get('/post/{id}/{name}', function ($id, $name) {
    return "This is a post number " . $id . " " . $name;
});

Route::get('/admin/posts/example/{id}/{name}', array('as' => 'admin.home', function ($id, $name) {
    // 把此url(http://blog-course.test/admin/posts/example) 整個name用array的方式給一變數名admin.home )
    // 這邊array的as不可變，否則route('admin.home')會不成立(他吃as這個關鍵字以給admin.home)
    // 若後面path還有參數如{id}{name}則須為 route("admin.home",['param1'=>'val1,'param2'=>'val2']);
    // 若是要取得url名字等同下面寫法
    $url = route("admin.home", ['id' => $id, 'name' => $name]);

    return "this url is " . $url;
}));

Route::get('/admin/posts/example/{id}', function ($id) {
    // 這邊的route name(admin.homes)不要跟上面取的名字一樣 不然會汙染到上面原本的結果
    $url = route('admin.homes', ['id' => $id]);
    return $url;
})->name('admin.homes');



// laravel 8 新 route

// 方法1 寫出完整路徑(app的a要大寫) (會直接就在PostController index()處就回傳給view了)
Route::get('/lar8/{id}', '\App\Http\Controllers\PostsController@index', function ($id) {
});

// 方法2 使用Use (會直接就在PostController index()處就回傳給view了)
use App\Http\Controllers\PostsController;

Route::get('/lar8/{id}', [PostsController::class, 'index'], function ($id) {
});

// 使用Route:resource 會可以follow php artisan route:list 的預設restful規則 (就不用一個一個定義就可對應method)
// 也就是用resource就follow route:list的規則 且生成controller時也  make:controller --resource 以對應
// 如訪問(get) /postss/{id} => show()  訪問(get) /posts/create =>create()
Route::resource('postss', PostsController::class);


// 不帶任何參數直接return view
Route::get('/contact', [PostsController::class, 'contact']);
// 帶參數return view 並使用樣板引擎接收此參數
Route::get('/contactPost/{id}', [PostsController::class, 'showPost']);
// 帶多參數return view 並使用樣板引擎接收此參數們
Route::get('/contactPost/{id}/{name}/{password}', [PostsController::class, 'showPost']);


/*
|--------------------------------------------------------------------------
|  DB Raw SQL Query
|--------------------------------------------------------------------------
*/

// 這行 use 可加可不加 (不加的話IDE會報錯但執行是正常)
// use Illuminate\Support\Facades\DB;
// Route::get('/insert', function () {
//     DB::insert('insert into posts (title, content) values (?, ?)', ['PHP with laravel', 'Laravel is the best thing that has happened to PHP']);
// });

// Route::get('/read', function () {
//     // 這個會是一個物件的陣列
//     $result = DB::select('select * from posts where id = ?', [1]);

//     // 這行跟他下面那行foreach的結果是一樣的
//     // return $result[0]->title;
//     foreach ($result as $post) {
//         return $post->title;
//     }
// });

// Route::get('/update', function () {
//     $updated = DB::update('update posts set title = "Update title" where id = ?', [1]);
//     // 這個結果會是1 就是代表成功update 反之則為0
//     return $updated;
// });

// Route::get('/delete', function () {
//     // 內建的delete會沒有from
//     $deleted = DB::delete('delete from posts where id = ?', [1]);
//     // 這個結果會是1 就是代表成功delete 反之則為0
//     return $deleted;
// });

/*
|--------------------------------------------------------------------------
|  Eloquent
|--------------------------------------------------------------------------
*/
// 這個在打Post時IDE出現選項時會自動import在最上面(我自己搬下來)
use App\Models\Post;

Route::get('/read', function () {
    // 這會找posts table裡面所有的資料
    $posts = Post::all();
    // 這會根據其id只找一筆資料 (找posts table中 其id是2的那一筆)
    // $post = Post::find(2);
    foreach ($posts as $post) {
        return $post->title;
    }
});

Route::get('/findwhere', function () {
    // $posts = Post::where('id',2)->orderBy('id','desc')->take(1)->get();
    // return $posts;

    try {
        // 用findOrFail的話找不到時會return exception 如果沒catch會直接回傳404
        // 而如果用find的話找不到則會是null
        $posts = Post::findOrFail(1);
        // 或是可以用where去下條件 (下面這個會報錯因為並沒有這個欄位)
        // $posts = Post::where('users_count','<',50)->firstOrFail();
        return $posts;
    } catch (Illuminate\Database\Eloquent\ModelNotFoundException $e) {
        return $e->getMessage();
    }
});

Route::get('/basicinsert', function () {
    // new 一個Post的物件 ( 也可以寫成 $post = new Post; //也就是說不用括號也可以)
    $post = new Post();

    $post->title = 'new Eloquent title insert';
    $post->content = 'Wow Eloquent is really cool ,look at this content';

    $post->save();
});

Route::get('/basicinsert2', function () {
    // 使用find找到id是第二的那筆，並把他的title跟content換掉
    $post = Post::find(2);

    $post->title = 'new Eloquent title insert 2';
    $post->content = 'Wow Eloquent is really cool ,look at this content 2';

    // 更新時會自動更新updated_at的時間
    $post->save();
});

Route::get('/create', function () {
    // 使用\反斜線 去跳脫特殊字元 如 '
    // 使用 create 的時候需要去model處註名title及content是可以去新增的欄位
    // 這個create只有給title跟content欄位但create及update的時間會自動建立
    Post::create(['title' => 'the create method', 'content' => 'Wow I\'m learing a lot']);
});

Route::get('/update', function () {
    // 兩個where的雙重判斷=>update該筆資料
    Post::where('id', 2)->where('is_admin', 0)->update(['title' => 'NEW PHP TITLE', 'content' => 'Update Content']);
});

Route::get('/delete', function () {
    // 找到ID為2的該筆資料並刪除
    $post = Post::find(2);
    $post->delete();
});

Route::get('/delete2', function () {
    // 刪除也可以直接用destroy直接刪除ID為 4 or 5的資料 (多筆時用陣列)
    // 單筆時寫Post::destroy(4)即可
    // 此刪除若遇到如只有id為5之資料 沒有4的那筆 仍會刪掉5的那筆資料並不會報錯
    Post::destroy([4, 5]);
    // 或也可以這樣寫 這個會把所有is_admin欄位為0的資料都刪掉
    // Post::where('is_admin',0)->delete();
});

Route::get('/softdelete', function () {
    // 找到id為7的那筆資料並將其視為軟刪除(該筆資料還在 並在delete_at那加上時間)
    // 如果7的這筆資料已經有deleted_at的時間(也就是說刪除過的話)
    // 那下面這行會報錯(會說你find的東西是為空 但你想刪除他)
    // 也可以使用Post::destroy(7) (不同的是當7已經被刪過了的話這個不會報錯)
    Post::find(7)->delete();
});

Route::get('/readsoftdelete', function () {
    // 如果find的這個資料ID有deleted_at的時間的話 這個結果會是null
    // 也就是說這個find會預設找沒被刪除(deleted_at)的資料
    // $post = Post::find(7);
    // return $post;

    // 這個結果會把刪除(deleted_at)+未刪除的一起顯示
    // $post = Post::withTrashed()->where('is_admin',0)->get();

    // 這個僅會顯示有刪除的(deleted_at)
    $post = Post::onlyTrashed()->where('is_admin', 0)->get();
    return $post;
});

Route::get('/restore', function () {
    // 把有刪除(deleted_at)+未刪除的一起撈出來 並把刪除的復原成未刪除
    Post::withTrashed()->where('is_admin', 0)->restore();
});

Route::get('/forcedelete', function () {
    // 找出有刪除(deleted_at)的資料並強制刪除此資料而非只加deleted_at的時間
    Post::onlyTrashed()->where('is_admin', 0)->forceDelete();
});

/*
|--------------------------------------------------------------------------
|  Eloquent Relationships
|--------------------------------------------------------------------------
*/
// 這個在打User時IDE出現選項時會自動import在最上面(我自己搬下來)
use App\Models\User;
// One to One relationship (由user的id找在post table有相同的user_id 之posts資料)
Route::get('/user/{id}/post', function ($id) {
    // 因為在UserModel內的post方法並沒有加get()去轉成json
    // 所以這邊取用post方法時不用加() 如果加了的話會報錯
    return User::find($id)->post;
});

// 反向取上面的 One to One (由post table的user_id找user)
Route::get('/post/{id}/user', function ($id) {
    // 這行會跟下面那行的結果是一樣的 只是因為postModel那邊return的直接是物件 所以直接拿其屬性就好 不用再說他是個方法再轉成json再拿物件屬性
    // print_r(Post::find($id)->user()->get()[0]->name);
    return Post::find($id)->user->name;
});

// One to Many Relationship
Route::get('/posts', function () {
    $user = User::find(1);
    // 之所以可以使用 $user->posts 而非 $user->posts() 是因為laravel將其轉換為一個property (lec 59時說的)
    foreach ($user->posts as $post) {
        echo $post->title . "<br>";
    }
});

// Get Many to Many Relationship
Route::get('/user/{id}/role', function ($id) {
    $user = User::find($id)->roles()->orderBy('id', 'desc')->get();

    return $user;
    // foreach ($user->roles as $role) {
    //     return $role->name;
    // }
});

// Accessing the intermediate table / pivot (由特定的user拿其多對多(role_user)表的內容)
Route::get('user/pivot', function () {
    $user = User::find(1);

    foreach ($user->roles as $role) {
        // 這個在userModel中若沒補lec63的那行只會顯示 role_user(多對多)表中的user_id及role_id
        echo $role->pivot;
        // 若需要顯示下面這行created_at欄位需要在userModel新增lec 63那行
        // echo $role->pivot->created_at;
    }
});


// 打Country時讓IDE跳一下在選會自動import(我把它搬下來)
use App\Models\Country;

Route::get('/user/country', function () {
    $country = Country::find(1);

    // 根據第一個Country透過在model裡寫的posts
    // 透過 Country => User => Post 的關係去找到 CountryID為1的使用者其postTitle
    // find the posts with related to the user from DR country
    foreach ($country->posts as $post) {
        return $post->title;
    }
});

// Polymorphic Relations add in lec 67
Route::get('user/photos', function () {
    $user = User::find(1);

    foreach ($user->photos as $photo) {
        echo $photo->path . "<br>";
    }
});
Route::get('post/{id}/photos', function ($id) {
    $post = Post::find($id);

    foreach ($post->photos as $photo) {
        echo $photo->path . "<br>";
    }
});
