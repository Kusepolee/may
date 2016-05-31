<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCustomerOrgTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('customer_org', function (Blueprint $table) {
            $table->increments('id');
            $table->string('name');
            $table->string('nic_name');
            $table->string('address');
            $table->string('postcode')->nullable();
            $table->string('tel')->nullable();
            $table->string('fax')->nullable();
            $table->string('logo')->nullable();
            $table->string('web')->nullable();
            $table->string('email')->nullable();
            $table->string('profile')->nullable();
            $table->string('content')->nullable();
            $table->integer('customer');
            $table->integer('supplier');
            $table->integer('gov');
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
        Schema::drop('customer_org');
    }
}
