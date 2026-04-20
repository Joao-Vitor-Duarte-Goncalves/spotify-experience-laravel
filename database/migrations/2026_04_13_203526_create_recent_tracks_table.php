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
        Schema::create('recent_tracks', function (Blueprint $table) {
            $table->id();
            
            // Relaciona a música ao usuário logado
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            $table->string('spotify_id');      // ID único da faixa no Spotify
            $table->string('name');            // Nome da música
            $table->string('artist');          // Nome do artista
            $table->string('album')->nullable();
            $table->string('image_url')->nullable(); 
            
            $table->timestamp('played_at');    // Quando foi ouvida
            $table->timestamps();              // created_at e updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('recent_tracks');
    }
};
