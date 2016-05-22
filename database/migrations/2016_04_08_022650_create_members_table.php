<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMembersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('members', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('work_id')->unique();// = userid, begin of wechat items
            $table->string('name');
            $table->string('department');
            $table->integer('position');
            $table->string('mobile')->unique();
            $table->integer('gender')->nullable();
            $table->string('email')->nullable();
            $table->string('weixinid')->nullable();
            $table->string('avatar_mediaid')->nullable();
            $table->string('extattr')->nullable();//end of wechat items
            $table->string('qq')->nullable();
            $table->string('password');
            $table->integer('state');
            $table->integer('show');
            $table->integer('new');
            $table->integer('admin')->nullable();
            $table->integer('created_by');
            $table->string('wechat_code')->nullable();
            $table->string('img')->nullable();
            $table->string('content')->nullable();
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
        Schema::drop('members');
    }
}
