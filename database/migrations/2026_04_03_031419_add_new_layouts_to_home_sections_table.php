<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        DB::statement("ALTER TABLE home_sections MODIFY COLUMN layout ENUM('card','list','editorial_hero','featured_stack','featured_list','grid_cards') NOT NULL DEFAULT 'card'");
    }

    public function down(): void
    {
        DB::statement("ALTER TABLE home_sections MODIFY COLUMN layout ENUM('card','list') NOT NULL DEFAULT 'card'");
    }
};
