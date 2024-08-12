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
            $table->string('nom')->index();
            $table->longText('description')->nullable();
            $table->string('reference')->unique()->index();
            $table->decimal('stock', 5, 2)->nullable();
            $table->integer('stock_alert')->default(0);
            $table->decimal('prix_ht', 10, 2)->nullable();
            $table->decimal('prix_ttc', 10, 2);
            $table->decimal('tva', 5, 2);
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('updated_by')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');
            $table->foreignId('brand_id')->nullable()->constrained('brands')->nullOnDelete();
            $table->foreignId('category_id')->nullable()->constrained('categories')->nullOnDelete();
            $table->unique(['reference', 'shop_id']);
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
