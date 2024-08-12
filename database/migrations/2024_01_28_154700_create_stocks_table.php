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
        Schema::create('stocks', function (Blueprint $table) {
            $table->id();
            $table->integer('quantity')->default(0);
            $table->decimal('prix_product_ht', 10, 2)->nullable();
            $table->decimal('prix_product_buy', 10, 2)->nullable();
            $table->longText('note')->nullable();
            $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');
            $table->foreignId('stock_status_id')->constrained()->onDelete(null);
            $table->foreignId('product_id')->constrained()->onDelete('cascade');
            $table->date('scheduled_date')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stocks');
    }
};
