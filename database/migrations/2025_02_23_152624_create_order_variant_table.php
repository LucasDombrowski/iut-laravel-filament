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
        Schema::create('order_variant', function (Blueprint $table) {
            $table->id();
            $table->foreignId("order_id")->constrained()->onDelete("cascade");
            $table->foreignId("variant_id")->constrained()->onDelete("cascade");
            // L'association commande-variante doit être unique
            $table->unique(["order_id", "variant_id"]);
            $table->integer("quantity");
            // On stocke ici le prix individuel de manière brute car celui-ci peut avoir changé depuis la commande.
            $table->bigInteger("unit_price");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('order_variant');
    }
};
