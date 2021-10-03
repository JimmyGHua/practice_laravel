<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddDeletedAtColumnToPostsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('posts', function (Blueprint $table) {
            //產生一個deleted_at欄位
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('posts', function (Blueprint $table) {
            //undo的話就刪除此deleted_at欄位
            $table->dropColumn('deleted_at');
            // laravel 8後可以直接這樣寫就會刪除deleted_at欄位
            // $table->dropSoftDeletes();
        });
    }
}
