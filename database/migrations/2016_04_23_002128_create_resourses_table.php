<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResoursesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resources', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('createBy');
            $table->string('name');
            $table->string('model');
            $table->string('unit');
            $table->decimal('notice', 15, 5)->nullable();
            $table->decimal('alert', 15, 5)->nullable();
            $table->decimal('remain', 15, 5)->nullable();
            $table->string('type');
            $table->string('content')->nullable();
            $table->integer('level');
            $table->integer('state');
            $table->integer('show');
            $table->string('img')->nullable();
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
        Schema::drop('resources');
    }
}
