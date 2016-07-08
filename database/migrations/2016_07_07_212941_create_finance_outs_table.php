<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFinanceOutsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('finance_outs', function (Blueprint $table) {
            $table->increments('out_id');
            $table->string('out_user');
            $table->decimal('out_amount', 15, 5)->nullable();
            $table->string('out_item');
            $table->string('out_date');
            $table->integer('out_bill');
            $table->integer('out_about');
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
        Schema::drop('finance_outs');
    }
}
