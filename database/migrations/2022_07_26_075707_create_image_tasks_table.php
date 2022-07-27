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
        Schema::create('image_tasks', function (Blueprint $table) {
            $table->id();
            $table->string('name', 30);
            $table->tinyInteger('type', unsigned: true)->comment('1-图片裁剪');
            $table->json('input')->comment('输入参数');
            $table->json('result')->comment('执行结果');
            $table->tinyInteger('status', unsigned: true)->comment('0-处理成功，1-未处理，2-处理中，3-处理失败');
            $table->timestamps();

            // index
            $table->index('name', 'idx_name');
            $table->index('type', 'idx_type');
            $table->index('status', 'idx_status');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('image_task');
    }
};
