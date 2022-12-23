<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('description')->nullable();
            $table->string('slug')->nullable();
            $table->string('ext')->nullable();
            $table->text('file')->nullable();
            $table->text('folder')->nullable();
            $table->text('domain')->nullable();
            $table->unsignedBigInteger('user_id')->nullable();
            $table->unsignedBigInteger('folder_id')->nullable();
            $table->string('path')->nullable();
            $table->integer('size')->nullable();
            $table->tinyInteger('is_front')->default(0);
            $table->timestamps();
            $table->index(['user_id', 'slug']);
            $table->index(['folder_id', 'slug']);
//            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('folder_id')->references('id')->on('folders')->cascadeOnDelete();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('files');
    }
};
