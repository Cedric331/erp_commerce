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
        Schema::create('commercants', function (Blueprint $table) {
            $table->id();
            $table->string('enseigne')->index();
            $table->string('email')->unique();
            $table->string('slug')->unique()->index();
            $table->string('siret')->index();
            $table->string('telephone')->nullable();
            $table->text('adresse')->nullable();
            $table->text('adresse_2')->nullable();
            $table->string('ville')->nullable();
            $table->string('code_postal')->nullable();
            $table->string('pays')->nullable();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->unique(['enseigne', 'user_id']);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('commercants');
    }
};
