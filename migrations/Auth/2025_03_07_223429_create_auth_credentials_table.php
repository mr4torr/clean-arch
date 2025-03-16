<?php

declare(strict_types=1);

use Auth\Domain\Enum\ProviderEnum;
use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('auth_credentials', function (Blueprint $table): void {
            $table->ulid('id')->primary();
            $table->foreignUlid('user_id')->constrained('auth_users')->onDelete('cascade');
            $table->string('hash');
            $table->string('provider', 12)->default(ProviderEnum::API->value);
            $table->boolean('status')->default(false);
            $table->timestampsTz();
        });

        // Schema::create('auth_reset_credentials', function (Blueprint $table) {
        //     $table->string('email')->primary();
        //     $table->string('token');
        //     $table->timestampTz('created_at')->nullable();
        // });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('auth_credentials');
        // Schema::dropIfExists("auth_reset_credentials");
    }
};
