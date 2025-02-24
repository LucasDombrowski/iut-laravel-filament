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
        Schema::create('discounts', function (Blueprint $table) {
            $table->id();
            // Le code n'est pas unique car celui-ci peut être mis en place pour d'autres offres promotionnelles à l'avenir.
            $table->string('code');
            $table->dateTime('start_at');
            $table->dateTime('end_at');
            $table->integer('min_price')->default(0);
            $table->string('type');
            $table->integer('value');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('discounts');
    }
};
