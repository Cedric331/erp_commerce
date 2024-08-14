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
        Schema::create('declination_products', function (Blueprint $table) {
            $table->id();
            $table->string('reference');
            $table->decimal('stock', 5, 2)->nullable();
            $table->integer('stock_alert')->default(0);
            $table->decimal('prix_ht', 10, 2)->nullable();
            $table->decimal('prix_ttc', 10, 2);
            $table->decimal('prix_buy', 10, 2)->nullable();
            $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');
            $table->foreignId('product_id')->constrained('products')->onDelete('cascade');
            $table->unique(['reference', 'shop_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('declination_products');
    }
};
