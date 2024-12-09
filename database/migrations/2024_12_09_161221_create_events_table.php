<?php

use App\Models\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up()
    {
        Schema::create('events', function (Blueprint $table) {
            $table->id();
            $table->foreignId('organizer_id')->constrained('users');
            $table->string('title');
            $table->text('description');
            $table->dateTime('start_date');
            $table->dateTime('end_date');
            $table->string('location');
            $table->integer('max_capacity');
            $table->string('category');
            $table->decimal('base_price', 10, 2);
            $table->boolean('is_featured')->default(false);
            $table->boolean('dynamic_pricing_enabled')->default(false);
            $table->string('ar_content_url')->nullable();
            $table->boolean('virtual_tour_enabled')->default(false);
            $table->dateTime('early_bird_deadline')->nullable();
            $table->decimal('early_bird_discount', 5, 2)->nullable();
            $table->json('venue_map_data')->nullable();
            $table->decimal('social_sharing_bonus', 5, 2)->default(0);
            $table->integer('group_discount_threshold')->default(0);
            $table->string('weather_policy')->nullable();
            $table->enum('status', ['draft', 'published', 'cancelled', 'completed'])->default('draft');
            $table->timestamps();
        });
    }

    public function down()
    {
        Schema::dropIfExists('events');
    }
};
