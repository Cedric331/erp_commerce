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
        Schema::create('details_ventes', function (Blueprint $table) {
            $table->id();
            $table->integer('quantite');
            $table->decimal('prix_ht', 10, 2);
            $table->decimal('prix_ttc', 10, 2);
            $table->decimal('tva', 5, 2);
            $table->foreignId('vente_id')->constrained('ventes')->onDelete('cascade');
            $table->foreignId('produit_id')->constrained('produits')->onDelete('cascade');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('details_ventes');
    }
};
