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
        Schema::create('home_sections', function (Blueprint $table): void {
            $table->id();
            $table->string('title');
            $table->boolean('show_title')->default(true);
            $table->boolean('show_view_all')->default(false);
            $table->string('view_all_url')->nullable();
            $table->enum('post_type', ['article', 'toplist'])->default('article');
            $table->enum('layout', ['card', 'list'])->default('card');
            $table->json('category_ids')->nullable();
            $table->enum('order_by', ['latest', 'oldest'])->default('latest');
            $table->unsignedTinyInteger('limit')->default(4);
            $table->boolean('is_active')->default(true);
            $table->unsignedSmallInteger('position')->default(0);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_sections');
    }
};
