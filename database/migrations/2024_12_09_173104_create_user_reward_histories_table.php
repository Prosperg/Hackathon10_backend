<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('user_reward_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_reward_id')->constrained()->onDelete('cascade');
            $table->integer('points');
            $table->string('reason');
            $table->integer('current_total');
            $table->json('metadata')->nullable();
            $table->timestamps();

            $table->index(['user_reward_id', 'created_at']);
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_reward_histories');
    }
};
