<?php

declare(strict_types=1);

use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('auth_users', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->string('name', 64);
            $table->string('email', 100)->unique()->index();
            $table->timestampTz('email_verified_at')->nullable();
            $table->timestampsTz();
        });

        Schema::create('auth_sessions', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')->constrained('auth_users')->onDelete('cascade');
            $table->string('ip_address', 45)->nullable();
            $table->string('user_agent', 100)->nullable();
            $table->timestampTz('last_activity')->nullable();
            $table->string('payload', 255)->nullable();
            $table->timestampTz('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_users');
        Schema::dropIfExists('auth_sessions');
    }
};
