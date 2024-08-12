<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('stock_statuses', function (Blueprint $table) {
            $table->id();
            $table->string('name')->index();
            $table->enum('type', ['entrée', 'sortie'])->default('entrée');
            $table->string('color')->nullable();
            $table->foreignId('shop_id')->constrained('shops')->onDelete('cascade');
            $table->unique(['name', 'shop_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('stock_statuses');
    }
};
