<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWxMiddlewareLoginUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wx_middleware_login_users', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('openid',30)->nullable()->comment('OPENID');
            $table->string('nickname',50)->nullable()->comment('昵称');
            $table->tinyInteger('sex')->nullable()->comment('性别');
            $table->string('language',10)->nullable()->comment('语言');
            $table->string('city',20)->nullable()->comment('城市');
            $table->string('province',20)->nullable()->comment('省份');
            $table->string('country',20)->nullable()->comment('国家');
            $table->string('headimgurl',190)->nullable()->comment('头像');
            $table->softDeletes();
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
        Schema::dropIfExists('wx_middleware_login_users');
    }
}
