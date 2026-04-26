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
        Schema::create('bundle_subscriptions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->foreignId('bundle_id')->constrained()->cascadeOnDelete();
            $table->enum('plan_type', ['monthly', 'annual']);
            $table->decimal('paid_amount', 12, 2);
            $table->string('currency', 3)->default('EGP');
            $table->string('gateway')->nullable(); // e.g. paymob, stripe
            $table->dateTime('expires_at')->nullable();
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bundle_subscriptions');
    }
};
