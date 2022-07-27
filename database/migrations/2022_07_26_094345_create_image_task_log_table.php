<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('image_task_log', function (Blueprint $table) {
            $table->id();
            $table->integer('task_id');
            $table->json('log')->comment('执行日志');
            $table->timestamps();

            // index
            $table->index('task_id', 'idx_task_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_task_log');
    }
};
