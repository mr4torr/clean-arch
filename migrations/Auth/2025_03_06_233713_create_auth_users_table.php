<?php

use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("auth_users", function (Blueprint $table) {
            $table->ulid("id")->primary();
            $table->string("name", 64);
            $table->string("email", 100)->unique()->index();
            $table->timestampTz('email_verified_at')->nullable();
            $table->timestampsTz();
        });

        Schema::create('auth_sessions', function (Blueprint $table) {
            $table->ulid('id')->primary();
            $table->foreignUlid("user_id")->nullable()->constrained("auth_users")->onDelete("cascade");
            $table->string('ip_address', 45)->nullable();
            $table->text('user_agent')->nullable();
            $table->timestampTz('last_activity');
            $table->text('payload');
            $table->timestampTz('created_at');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("auth_users");
        Schema::dropIfExists("auth_sessions");
    }
};
