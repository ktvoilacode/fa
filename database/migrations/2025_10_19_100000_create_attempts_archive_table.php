<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAttemptsArchiveTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('attempts_archive', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->integer('test_id')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->integer('mcq_id')->nullable();
            $table->integer('fillup_id')->nullable();
            $table->string('qno', 50)->default('');
            $table->longText('response')->nullable();
            $table->longText('answer')->nullable();
            $table->integer('accuracy')->nullable();
            $table->timestamps();
            $table->string('session_id')->nullable();
            $table->integer('dynamic')->nullable();
            $table->float('score')->default(1);
            $table->longText('comment')->nullable();
            $table->integer('status')->default(1);
            $table->longText('marking')->nullable();

            // Add indexes for common queries on archive
            $table->index('created_at', 'idx_archive_created_at');
            $table->index(['user_id', 'created_at'], 'idx_archive_user_created');
            $table->index(['test_id', 'created_at'], 'idx_archive_test_created');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('attempts_archive');
    }
}
