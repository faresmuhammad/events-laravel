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
        Schema::create('ticket_codes', function (Blueprint $table) {
            $table->string('code')->primary();
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('ticket_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->dateTime('attended_at')->nullable();
            $table->boolean('purchased')->default(false);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ticket_codes');
    }
};
