<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        // Categorias raiz
        $tech = Category::create([
            'name'             => 'Tecnologia',
            'slug'             => 'tecnologia',
            'description'      => 'Artigos sobre tecnologia, gadgets e inovação',
            'color'            => '#6366f1',
            'meta_title'       => 'Tecnologia — Nerdopina',
            'meta_description' => 'Os melhores artigos sobre tecnologia, gadgets e inovação',
        ]);

        $games = Category::create([
            'name'             => 'Games',
            'slug'             => 'games',
            'description'      => 'Reviews, listas e novidades do mundo dos games',
            'color'            => '#10b981',
            'meta_title'       => 'Games — Nerdopina',
            'meta_description' => 'Reviews, top lists e novidades do mundo dos games',
        ]);

        // Subcategorias
        Category::create([
            'parent_id'   => $tech->id,
            'name'        => 'Smartphones',
            'slug'        => 'smartphones',
            'description' => 'Tudo sobre smartphones e dispositivos móveis',
            'color'       => '#818cf8',
        ]);

        Category::create([
            'parent_id'   => $games->id,
            'name'        => 'RPG',
            'slug'        => 'rpg',
            'description' => 'Os melhores RPGs de todos os tempos',
            'color'       => '#34d399',
        ]);
    }
}