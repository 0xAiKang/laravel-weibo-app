<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStatusesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('statuses', function (Blueprint $table) {
            // 自增id
            $table->increments("id");
            // 微博内容
            $table->text("content");
            // 用户id（增加索引
            $table->integer("user_id")->index();
            // 创建时间
            $table->index(["created_at"]);
            // timestamps 方法会生成 created_at 和 updated_at 字段
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('statuses');
    }
}
