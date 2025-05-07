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
        Schema::table('orders', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn('scheduled_at');
            $table->decimal('price', 8, 2)->after('status');
            $table->integer('hours')->after('price');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('orders', function (Illuminate\Database\Schema\Blueprint $table) {
            $table->dropColumn(['price', 'hours']);
            $table->timestamp('scheduled_at')->nullable();
        });
    }
};
