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
        Schema::create('posts', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained()->cascadeOnDelete();
            $table->enum('type', ['article', 'toplist'])->default('article');
            $table->string('title');
            $table->string('slug')->unique();
            $table->text('excerpt');               // resumo para listagens e description
            $table->longText('content');           // corpo do artigo (HTML)
            $table->string('featured_image')->nullable();
            $table->string('featured_image_alt')->nullable();  // alt text para SEO

            // SEO fields
            $table->string('meta_title')->nullable();          // se vazio, usa title
            $table->text('meta_description')->nullable();      // se vazio, usa excerpt
            $table->string('canonical_url')->nullable();       // URL canônica opcional
            $table->enum('robots', ['index,follow', 'noindex,nofollow', 'noindex,follow'])
                ->default('index,follow');

            // Open Graph overrides
            $table->string('og_title')->nullable();
            $table->string('og_image')->nullable();            // imagem específica para social

            $table->enum('status', ['draft', 'published'])->default('draft');
            $table->timestamp('published_at')->nullable();
            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index('slug');
            $table->index('category_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('posts');
    }
};
