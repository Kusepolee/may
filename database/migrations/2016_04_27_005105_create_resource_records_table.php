<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateResourceRecordsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resource_records', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('resource');
            $table->integer('type');
            $table->integer('out_or_in');
            $table->decimal('amount', 15, 5);
            $table->integer('from')->nullable;
            $table->integer('to')->nullable();
            $table->integer('for');
            $table->string('content')->nullable();
            $table->string('token')->nullable();
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
        Schema::drop('resource_records');
    }
}
