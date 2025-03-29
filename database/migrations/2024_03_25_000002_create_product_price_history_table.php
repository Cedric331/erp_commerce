<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('product_price_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->decimal('old_price_excl_tax', 10, 2);
            $table->decimal('new_price_excl_tax', 10, 2);
            $table->decimal('old_price_incl_tax', 10, 2);
            $table->decimal('new_price_incl_tax', 10, 2);
            $table->decimal('old_tax_rate', 5, 2);
            $table->decimal('new_tax_rate', 5, 2);
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('product_price_history');
    }
};
