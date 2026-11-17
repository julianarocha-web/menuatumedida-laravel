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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->restrictOnDelete();
            $table->uuid('checkout_token')->unique();
            $table->string('status')->default('pending_payment');
            $table->unsignedBigInteger('total_cents');
            $table->string('currency', 3)->default('ARS');
            $table->string('mp_order_id')->nullable()->unique();
            $table->timestamp('reserved_until');
            $table->index(['status', 'reserved_until']);
            $table->timestamps();
        });

        Schema::create('order_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->restrictOnDelete();
            $table->foreignId('plato_id')->constrained()->restrictOnDelete();
            $table->string('nombre');
            $table->unsignedInteger('quantity');
            $table->unsignedBigInteger('unit_cents');
            $table->timestamps();
            $table->unique(['order_id', 'plato_id']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_items');
        Schema::dropIfExists('orders');
    }
};
