<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCpanelsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cpanels', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->string('domain')->unique();
            $table->integer('client_id');
            $table->string('username');
            $table->string('password');
            $table->string('plan');
            $table->date('start_time');
            $table->double('price');
            $table->date('renew_date');
            $table->integer('recurring');
            $table->timestamps();
            $table->enum('status',['0','1','-1'])->default('1');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cpanels');
    }
}
