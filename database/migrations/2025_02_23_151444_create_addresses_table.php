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
        Schema::create('addresses', function (Blueprint $table) {
            $table->id();
            $table->foreignId("user_id")->constrained()->onDelete("cascade");
            // On suppose ici que le numéro peut contenir des lettres
            $table->string("number");
            $table->string("street");
            $table->string("complement")->nullable();
            $table->string("city");
            // On suppose ici que le code postal peut contenir des lettres
            $table->string("zip_code");
            // On utilise un code pays afin de gérer d'éventuelles traductions ultérieurement
            $table->string("country_code");
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('addresses');
    }
};
