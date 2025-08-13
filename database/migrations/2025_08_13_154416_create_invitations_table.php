<?php

declare(strict_types=1);

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
        Schema::create('invitations', function (Blueprint $t): void {
            $t->id();
            $t->foreignId('inviter_id')->constrained('users')->cascadeOnDelete();
            $t->string('token')->unique();
            $t->string('email')->nullable();
            $t->unsignedTinyInteger('max_uses')->default(1);
            $t->unsignedTinyInteger('uses')->default(0);
            $t->timestamp('expires_at')->nullable();
            $t->timestamp('used_at')->nullable();
            $t->string('role')->default('member');
            $t->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('invitations');
    }
};
