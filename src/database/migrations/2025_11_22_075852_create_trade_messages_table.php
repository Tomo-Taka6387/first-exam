<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trade_messages', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trade_id');
            $table->unsignedBigInteger('sender_id');
            $table->text('message')->nullable(false);
            $table->string('img_path')->nullable();
            $table->boolean('is_read')->default(0);
            $table->timestamps();

            $table->foreign('trade_id')->references('id')->on('trades')->onDelete('cascade');
            $table->foreign('sender_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trade_messages');
    }
};
