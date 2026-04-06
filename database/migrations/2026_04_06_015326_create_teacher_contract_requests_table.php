<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Domains\Identity\Enums\RequestStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('teacher_contract_requests', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('type'); // hourly | per_session
            $table->decimal('rate', 10, 2);
            $table->decimal('max_daily_hours', 4, 2);
            $table->json('available_days'); // [0=Sun, 1=Mon, ...]
            $table->char('currency', 3)->default('EGP');
            $table->string('status')->default(RequestStatus::PENDING->value);
            $table->foreignId('reviewer_id')->nullable()->constrained('users')->onDelete('set null');
            $table->text('reviewer_notes')->nullable();
            $table->timestamp('requested_at')->useCurrent();
            $table->timestamp('reviewed_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('teacher_contract_requests');
    }
};
