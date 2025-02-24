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
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->onDelete("cascade");
            $table->foreignId("discount_id")->nullable()->constrained()->onDelete("set null");
            $table->string("status");
            // Dans le cas présent, l'adresse est enegistrée de manière brute car l'instance de donnée peut avoir été supprimée.
            // On suppose ici que le numéro peut contenir des lettres
            $table->string("address_number");
            $table->string("address_street");
            $table->string("address_complement")->nullable();
            $table->string("address_city");
            // On suppose ici que le code postal peut contenir des lettres
            $table->string("address_zip_code");
            // On utilise un code pays afin de gérer d'éventuelles traductions ultérieurement
            $table->string("address_country_code");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('orders');
    }
};
