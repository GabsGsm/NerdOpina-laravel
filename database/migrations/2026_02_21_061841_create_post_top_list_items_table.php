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
        Schema::create('post_top_list_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('post_id')->constrained()->cascadeOnDelete();
            $table->unsignedTinyInteger('rank');        // posição na lista (1, 2, 3...)
            $table->string('title');                    // nome do item
            $table->string('image_url')->nullable();    // URL da imagem
            $table->string('external_url')->nullable(); // link externo do item
            $table->text('description')->nullable();    // descrição curta para o card
            $table->timestamps();

            $table->index(['post_id', 'rank']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('post_top_list_items');
    }
};
