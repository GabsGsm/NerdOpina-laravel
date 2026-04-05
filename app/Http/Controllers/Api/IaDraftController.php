<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class IaDraftController extends Controller
{
    public function __invoke(Request $request)
    {
        $validated = $request->validate([
            'title'              => ['required', 'string', 'max:255'],
            'slug'               => ['required', 'string', 'regex:/^[a-z0-9\-]+$/', 'unique:posts,slug'],
            'excerpt'            => ['required', 'string', 'max:500'],
            'content'            => ['required', 'string'],
            'category'           => ['required', 'string'],
            'meta_title'         => ['nullable', 'string', 'max:255'],
            'meta_description'   => ['nullable', 'string', 'max:500'],
            'og_title'           => ['nullable', 'string', 'max:255'],
            'featured_image_url' => ['nullable', 'url'],
            'featured_image_alt' => ['nullable', 'string', 'max:255'],
        ]);

        // ── Resolve categoria pelo slug ou id numérico ─────────────────
        $category = is_numeric($validated['category'])
            ? Category::findOrFail($validated['category'])
            : Category::where('slug', $validated['category'])->firstOrFail();

        // ── Baixa imagem do Unsplash e salva no storage local ──────────
        $featuredImage = null;

        if (! empty($validated['featured_image_url'])) {
            try {
                $response = Http::timeout(15)
                    ->withHeaders(['User-Agent' => 'Briff/1.0 (nerd-opina.com.br)'])
                    ->get($validated['featured_image_url']);

                if ($response->successful()) {
                    $filename = 'posts/' . Str::random(40) . '.jpg';
                    Storage::disk('public')->put($filename, $response->body());
                    $featuredImage = $filename;
                }
            } catch (\Exception $e) {
                // Loga mas não interrompe — post vai sem imagem
                Log::warning('[IA Draft] Falha ao baixar imagem', [
                    'url'   => $validated['featured_image_url'],
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // ── Cria o rascunho — NUNCA publica direto ────────────────────
        $post = Post::create([
            'category_id'        => $category->id,
            'type'               => 'article',
            'title'              => $validated['title'],
            'slug'               => $validated['slug'],
            'excerpt'            => $validated['excerpt'],
            'content'            => $validated['content'],
            'featured_image'     => $featuredImage,
            'featured_image_alt' => $validated['featured_image_alt'] ?? null,
            'meta_title'         => $validated['meta_title']         ?? null,
            'meta_description'   => $validated['meta_description']   ?? null,
            'og_title'           => $validated['og_title']           ?? $validated['title'],
            'robots'             => 'index,follow',
            'status'             => 'draft',
            'published_at'       => null,
        ]);

        return response()->json([
            'ok'       => true,
            'post_id'  => $post->id,
            'edit_url' => url('/admin/posts/' . $post->id . '/edit'),
        ], 201);
    }
}
