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
        Schema::table('users', function (Blueprint $table) {
            $table->string('first_name')->after('id')->nullable();
            $table->string('middle_name')->after('first_name')->nullable();
            $table->string('last_name')->after('middle_name')->nullable();
            $table->date('date_of_birth')->after('last_name')->nullable();
            $table->timestamp('last_login_at')->nullable()->after('email');
            $table->string('phone_number')->after('last_login_at')->nullable();
            $table->enum('gender', ['male', 'female', 'other'])->after('date_of_birth')->nullable();
            $table->enum('status', ['active', 'inactive', 'banned'])->default('active')->after('password');
            
            // Address Value Object fields
            $table->string('country')->nullable();
            $table->string('city')->nullable();
            $table->string('street_address')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn([
                'first_name',
                'middle_name',
                'last_name',
                'date_of_birth',
                'last_login_at',
                'phone_number',
                'gender',
                'status',
                'country',
                'city',
                'street_address'
            ]);
        });
    }
};
