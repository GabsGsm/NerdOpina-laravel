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
        Schema::create('home_sidebar_widgets', function (Blueprint $table): void {
            $table->id();
            $table->foreignId('container_id')->constrained('home_containers')->cascadeOnDelete();
            $table->enum('type', ['categories', 'popular_posts', 'featured_toplist'])->default('categories');
            $table->string('title')->nullable();
            $table->unsignedTinyInteger('limit')->default(5);
            $table->unsignedSmallInteger('position')->default(0);
            $table->boolean('is_active')->default(true);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('home_sidebar_widgets');
    }
};
