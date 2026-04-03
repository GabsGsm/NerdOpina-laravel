<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('home_sections', function (Blueprint $table): void {
            $table->foreignId('container_id')
                ->nullable()
                ->after('id')
                ->constrained('home_containers')
                ->nullOnDelete();
        });

        // Cria um container padrão "box" e migra as seções existentes para ele
        $containerId = DB::table('home_containers')->insertGetId([
            'type' => 'box',
            'position' => 0,
            'is_active' => true,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        DB::table('home_sections')
            ->whereNull('container_id')
            ->update(['container_id' => $containerId]);
    }

    public function down(): void
    {
        Schema::table('home_sections', function (Blueprint $table): void {
            $table->dropForeign(['container_id']);
            $table->dropColumn('container_id');
        });
    }
};
