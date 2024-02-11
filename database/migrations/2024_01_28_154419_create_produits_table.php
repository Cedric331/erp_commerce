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
        Schema::create('produits', function (Blueprint $table) {
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
            $table->foreignId('commercant_id')->constrained('commercants')->onDelete('cascade');
            $table->foreignId('fournisseur_id')->nullable()->constrained('fournisseurs')->nullOnDelete();
            $table->foreignId('categorie_id')->nullable()->constrained('categorie_produits')->nullOnDelete();
            $table->unique(['reference', 'commercant_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('produits');
    }
};
