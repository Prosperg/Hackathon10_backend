<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->foreignId('event_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->nullable()->constrained();
            $table->foreignId('ticket_category_id')->constrained();
            $table->decimal('price', 10, 2);
            $table->enum('status', ['available', 'reserved', 'sold', 'used', 'cancelled'])->default('available');
            $table->string('seat_number')->nullable();
            $table->binary('qr_code')->nullable();
            $table->uuid('unique_identifier')->unique();
            $table->dateTime('purchase_date')->nullable();
            $table->dateTime('check_in_time')->nullable();
            $table->json('special_requests')->nullable();
            $table->boolean('is_transferable')->default(true);
            $table->dateTime('transfer_deadline')->nullable();
            $table->boolean('ar_experience_enabled')->default(false);
            $table->boolean('social_sharing_done')->default(false);
            $table->string('group_booking_id')->nullable();
            $table->timestamps();

            $table->index(['event_id', 'status']);
            $table->index('unique_identifier');
        });
    }

    public function down()
    {
        Schema::dropIfExists('tickets');
    }
};
