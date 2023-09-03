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
        Schema::create('tickets', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->text('description')->nullable();
            $table->double('price');
            $table->integer('quantity_available')->default(0);
            $table->integer('quantity_sold')->default(0);
            $table->integer('quantity_attended')->default(0);
            $table->dateTime('start_sale_date')->nullable();
            $table->dateTime('end_sale_date')->nullable();
            $table->boolean('is_hidden')->default(true);
            $table->boolean('on_sale')->default(false);
            $table->foreignId('user_id')->constrained()->cascadeOnUpdate()->nullOnDelete();
            $table->foreignId('event_id')->constrained()->cascadeOnUpdate()->cascadeOnDelete();
            $table->timestamps();
            $table->dateTime('deleted_at')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tickets');
    }
};
