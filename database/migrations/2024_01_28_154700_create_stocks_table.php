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
            $table->decimal('price_buy', 10, 2)->nullable(); // Prix d'achat HT
            $table->decimal('price_ht', 10, 2); // Prix de vente HT
            $table->decimal('price_ttc', 10, 2); // Prix de vente TTC
            $table->longText('note')->nullable();
            $table->date('date_process')->nullable();
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
