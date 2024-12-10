<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('motorcycles', function (Blueprint $table) {
            $table->id();
            $table->string('phone_number');
            $table->string('motorcycle_number');
            $table->string('photo_path');
            $table->boolean('payment_status')->default(false);
            $table->foreignId('user_id')->constrained(); // L'utilisateur qui a enregistré la moto
            $table->foreignId('ticket_category_id')->constrained();
            $table->string('ticket_code')->unique()->nullable();
            $table->string('qr_code_path')->nullable();
            $table->enum('status', ['in_custody', 'returned'])->default('in_custody');
            $table->timestamp('entry_time');
            $table->timestamp('return_time')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();

            $table->index('phone_number');
            $table->index('motorcycle_number');
            $table->index('ticket_code');
            $table->index('status');
        });
    }

    public function down()
    {
        Schema::dropIfExists('motorcycles');
    }
};
