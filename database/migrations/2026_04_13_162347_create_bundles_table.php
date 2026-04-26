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
        Schema::create('bundles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('program_id')->nullable()->constrained('programs')->onDelete('cascade');
            $table->json('name'); 
            $table->integer('duration_minutes');
            $table->integer('sessions_count');
            $table->json('features'); 
            $table->decimal('monthly_price_egp', 12, 2);
            $table->decimal('monthly_price_usd', 12, 2);
            $table->boolean('is_best_seller')->default(false);
            $table->boolean('is_active')->default(true);
            $table->integer('order')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bundles');
    }
};
