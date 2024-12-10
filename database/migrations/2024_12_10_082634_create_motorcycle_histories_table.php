<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('motorcycle_histories', function (Blueprint $table) {
            $table->id();
            $table->foreignId('motorcycle_id')->constrained();
            $table->foreignId('user_id')->constrained(); // L'utilisateur qui a effectué l'action
            $table->enum('action', ['check_in', 'payment', 'return']);
            $table->timestamp('action_time');
            $table->text('notes')->nullable();
            $table->json('metadata')->nullable(); // Pour stocker des informations supplémentaires
            $table->timestamps();

            $table->index(['motorcycle_id', 'action']);
            $table->index('action_time');
        });
    }

    public function down()
    {
        Schema::dropIfExists('motorcycle_histories');
    }
};
