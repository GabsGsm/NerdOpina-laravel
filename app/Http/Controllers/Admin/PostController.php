<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StorePostRequest;
use App\Http\Requests\Admin\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $posts = Post::with('category')
            ->when($request->type, fn ($q, $v) => $q->where('type', $v))
            ->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->category, fn ($q, $v) => $q->where('category_id', $v))
            ->latest()
            ->paginate(20)
            ->withQueryString();

        $categories = Category::orderBy('name')->get();

        return view('admin.posts.index', compact('posts', 'categories'));
    }

    public function create(): View
    {
        $categories = Category::orderBy('name')->get();

        return view('admin.posts.create', compact('categories'));
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $this->uploadImage($request->file('featured_image'));
        }

        $items = $data['items'] ?? [];
        unset($data['items'], $data['items_images']);

        $items = $this->processItemImages($items, $request->file('items_images', []));

        DB::transaction(function () use ($data, $items): void {
            $post = Post::create($data);

            if ($post->isTopList() && ! empty($items)) {
                foreach ($items as $index => $item) {
                    $post->topListItems()->create(array_merge($item, ['rank' => $index + 1]));
                }
            }
        });

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post criado com sucesso.');
    }

    public function edit(Post $post): View
    {
        $post->load('topListItems');
        $categories = Category::orderBy('name')->get();

        return view('admin.posts.edit', compact('post', 'categories'));
    }

    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $data = $request->validated();

        if ($request->hasFile('featured_image')) {
            $this->deleteImage($post->featured_image);
            $data['featured_image'] = $this->uploadImage($request->file('featured_image'));
        }

        $items = $data['items'] ?? [];
        unset($data['items'], $data['items_images']);

        // Collect existing local item images before overwriting
        $oldLocalImages = $post->topListItems
            ->pluck('image_url')
            ->filter(fn ($url) => $url && ! str_starts_with($url, 'http'))
            ->values()
            ->toArray();

        $items = $this->processItemImages($items, $request->file('items_images', []));

        // Determine which old local images are no longer referenced
        $newLocalImages = collect($items)
            ->pluck('image_url')
            ->filter(fn ($url) => $url && ! str_starts_with($url, 'http'))
            ->values()
            ->toArray();

        DB::transaction(function () use ($post, $data, $items): void {
            $post->update($data);

            if ($post->isTopList()) {
                $post->topListItems()->delete();
                foreach ($items as $index => $item) {
                    $post->topListItems()->create(array_merge($item, ['rank' => $index + 1]));
                }
            }
        });

        // Clean up orphaned local item images
        foreach (array_diff($oldLocalImages, $newLocalImages) as $orphan) {
            Storage::disk('public')->delete($orphan);
        }

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post atualizado com sucesso.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        $this->deleteImage($post->featured_image);

        $post->delete(); // cascadeOnDelete cuida dos topListItems

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post excluído com sucesso.');
    }

    private function uploadImage(\Illuminate\Http\UploadedFile $file, string $folder = 'posts'): string
    {
        $filename = Str::random(40).'.'.$file->extension();

        return $file->storeAs($folder, $filename, 'public');
    }

    private function deleteImage(?string $path): void
    {
        if ($path && ! str_starts_with($path, 'http') && Storage::disk('public')->exists($path)) {
            Storage::disk('public')->delete($path);
        }
    }

    /**
     * @param  array<int, array<string, mixed>>  $items
     * @param  array<int, \Illuminate\Http\UploadedFile|null>  $files
     * @return array<int, array<string, mixed>>
     */
    private function processItemImages(array $items, array $files): array
    {
        foreach ($files as $index => $file) {
            if ($file && $file->isValid() && isset($items[$index])) {
                $items[$index]['image_url'] = $this->uploadImage($file, 'toplists');
            }
        }

        return $items;
    }
}
