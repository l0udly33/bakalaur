<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('trainer_profiles', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id')->unique(); // link to users table

            $table->string('profile_picture')->nullable(); // file path or URL
            $table->text('description')->nullable();
            $table->string('languages')->nullable(); // store as comma-separated string
            $table->enum('rank', [
                'Iron',
                'Bronze',
                'Silver',
                'Gold',
                'Platinum',
                'Diamond',
                'Ascendant',
                'Immortal',
                'Radiant'
            ])->nullable();

            // Pricing: up to 3 JSON entries like [{hours: 1, price: 10}, ...]
            $table->json('pricing')->nullable();

            // Availability: example JSON structure for days/hours
            $table->json('availability')->nullable();

            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('trainer_profiles');
    }
};
