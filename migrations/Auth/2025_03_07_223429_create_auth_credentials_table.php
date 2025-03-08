<?php

use Auth\Domain\Enum\ProviderEnum;
use Hyperf\Database\Schema\Schema;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create("auth_credentials", function (Blueprint $table) {
            $table->ulid("id")->primary();
            $table->foreignUlid("user_id")->constrained("auth_users")->onDelete("cascade");
            $table->string("hash");
            $table->string("provider", 12)->default(ProviderEnum::API->value);
            $table->timestampsTz();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists("auth_credentials");
    }
};
