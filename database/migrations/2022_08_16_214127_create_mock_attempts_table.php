<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMockAttemptsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('mock_attempts', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('mock_id');
            $table->integer('user_id');
            $table->string('t1')->nullable();
            $table->string('t2')->nullable();
            $table->string('t3')->nullable();
            $table->string('t4')->nullable();
            $table->string('t1_score')->nullable();
            $table->string('t2_score')->nullable();
            $table->string('t3_score')->nullable();
            $table->string('t4_score')->nullable();
            $table->integer('status');
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
        Schema::dropIfExists('mock_attempts');
    }
}
