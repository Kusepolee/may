<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProductQuotaTable extends Migration
{
    /**
     * 产品配额表
     *
     * @return void
     */
    public function up()
    {
        Schema::create('product_quota', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->integer('for');
            $table->integer('resource')->nullable();
            $table->decimal('amount', 15, 5)->nullable();
            $table->integer('type')->nullable();
            $table->decimal('time', 5, 2)->nullable();
            $table->integer('work_type')->nullable();
            $table->integer('createBy');
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
        Schema::drop('product_quota');
    }
}
