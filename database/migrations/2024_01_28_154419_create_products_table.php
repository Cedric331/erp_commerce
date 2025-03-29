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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('shop_id')->constrained()->onDelete('cascade');
            $table->foreignId('category_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('brand_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('storage_id')->nullable()->constrained()->nullOnDelete();
            $table->string('name');
            $table->string('type')->nullable();
            $table->text('description')->nullable();
            $table->string('reference')->nullable();
            $table->string('barcode')->nullable()->unique();

            // Prix et taxes
            $table->decimal('price_buy', 10, 2)->nullable(); // Prix d'achat HT
            $table->decimal('price_ht', 10, 2); // Prix de vente HT
            $table->decimal('price_ttc', 10, 2); // Prix de vente TTC
            $table->decimal('tva', 5, 2)->default(20.00);

            // Caractéristiques physiques
            $table->string('size')->nullable();
            $table->string('color')->nullable();
            $table->string('weight')->nullable();
            $table->json('attributes')->nullable();

            // Gestion du stock
            $table->decimal('stock', 10, 2)->default(0);
            $table->decimal('stock_alert', 10, 2)->default(0);
            $table->string('unit')->default('unité');

            // Statut et traçabilité
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
