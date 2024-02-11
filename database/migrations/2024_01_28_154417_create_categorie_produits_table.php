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
        Schema::create('categorie_produits', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->integer('alert_stock')->default(0);
            $table->foreignId('commercant_id')->constrained()->onDelete('cascade');
            $table->unique(['name', 'commercant_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('categorie_produits');
    }
};
