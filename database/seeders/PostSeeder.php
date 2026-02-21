<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Post;
use App\Models\PostTopListItem;

class PostSeeder extends Seeder
{
    public function run(): void
    {
        $tech  = Category::where('slug', 'tecnologia')->first();
        $games = Category::where('slug', 'games')->first();

        // Artigo normal
        Post::create([
            'category_id'  => $tech->id,
            'type'         => 'article',
            'title'        => 'O futuro da inteligência artificial em 2025',
            'slug'         => 'futuro-inteligencia-artificial-2025',
            'excerpt'      => 'Como a IA está transformando indústrias inteiras e o que esperar nos próximos anos.',
            'content'      => '<p>A inteligência artificial deixou de ser ficção científica e se tornou parte do cotidiano...</p><p>Em 2025, modelos de linguagem, visão computacional e automação estão redefinindo como trabalhamos e nos relacionamos com a tecnologia.</p>',
            'status'       => 'published',
            'published_at' => now()->subDays(2),
        ]);

        Post::create([
            'category_id'  => $tech->id,
            'type'         => 'article',
            'title'        => 'Os melhores smartphones para comprar agora',
            'slug'         => 'melhores-smartphones-comprar-agora',
            'excerpt'      => 'Selecionamos os smartphones com melhor custo-benefício do mercado atual.',
            'content'      => '<p>Escolher um smartphone pode ser difícil com tantas opções disponíveis...</p>',
            'status'       => 'published',
            'published_at' => now()->subDays(5),
        ]);

        // Post TopList
        $toplist = Post::create([
            'category_id'  => $games->id,
            'type'         => 'toplist',
            'title'        => 'Os 5 melhores RPGs de todos os tempos',
            'slug'         => 'melhores-rpgs-todos-os-tempos',
            'excerpt'      => 'Uma lista definitiva com os RPGs que definiram o gênero e marcaram gerações.',
            'content'      => '<p>O gênero RPG tem uma história rica e cheia de títulos memoráveis...</p><p>Separamos os cinco jogos que todo fã do gênero precisa conhecer.</p>',
            'status'       => 'published',
            'published_at' => now()->subDay(),
        ]);

        // Itens da toplist
        $items = [
            [1, 'The Witcher 3: Wild Hunt',      'https://upload.wikimedia.org/wikipedia/en/0/0c/Witcher_3_cover_art.jpg', 'https://www.thewitcher.com', 'Uma obra-prima do RPG moderno com mundo aberto imenso e narrativa profunda.'],
            [2, 'Elden Ring',                     'https://upload.wikimedia.org/wikipedia/en/b/b9/Elden_Ring_Box_art.jpg',  'https://en.bandainamcoent.eu/elden-ring', 'A fusão perfeita entre souls-like e RPG de mundo aberto.'],
            [3, 'Baldur\'s Gate 3',               'https://upload.wikimedia.org/wikipedia/en/5/5f/Baldur%27s_Gate_3_cover_art.jpg', 'https://baldursgate3.game', 'O melhor RPG de turno da geração com liberdade de escolha absurda.'],
            [4, 'Final Fantasy VII Remake',       null, 'https://www.finalfantasy.com', 'Uma reinvenção respeitosa do clássico que encantou toda uma geração.'],
            [5, 'Dark Souls: Remastered',         null, 'https://www.darksouls.jp', 'O jogo que redefiniu dificuldade e recompensa nos games modernos.'],
        ];

        foreach ($items as [$rank, $title, $image, $url, $desc]) {
            PostTopListItem::create([
                'post_id'      => $toplist->id,
                'rank'         => $rank,
                'title'        => $title,
                'image_url'    => $image,
                'external_url' => $url,
                'description'  => $desc,
            ]);
        }
    }
}