<?php

declare(strict_types=1);

use Auth\Domain\Enum\UserStatusEnum;
use Hyperf\Database\Migrations\Migration;
use Hyperf\Database\Schema\Blueprint;
use Hyperf\Database\Schema\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('auth_users', function (Blueprint $table): void {
            $table
                ->enum('status', array_column(UserStatusEnum::cases(), 'value'))
                ->default(UserStatusEnum::default()->value);
            $table->string('reason_status')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('auth_users', function (Blueprint $table): void {
            $table->dropColumn('status');
            $table->dropColumn('reason_status');
        });
    }
};
