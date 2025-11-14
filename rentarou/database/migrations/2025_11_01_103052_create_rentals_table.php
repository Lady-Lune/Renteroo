<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('rentals', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->foreignId('item_id')->constrained()->onDelete('cascade');
            $table->date('start_date');
            $table->date('end_date');
            $table->date('actual_return_date')->nullable();
            $table->integer('quantity')->default(1);
            $table->decimal('daily_rate', 10, 2);
            $table->decimal('total_amount', 10, 2);
            $table->decimal('late_fee', 10, 2)->default(0);
            $table->decimal('damage_fee', 10, 2)->default(0);
            $table->enum('status', ['pending', 'active', 'completed', 'overdue', 'cancelled'])->default('pending');
            $table->text('notes')->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('rentals');
    }
};