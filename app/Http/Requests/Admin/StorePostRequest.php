<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class StorePostRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'category_id' => ['required', 'integer', 'exists:categories,id'],
            'type' => ['required', 'in:article,toplist'],
            'title' => ['required', 'string', 'max:255'],
            'slug' => ['required', 'string', 'max:255', 'unique:posts,slug', 'regex:/^[a-z0-9\-]+$/'],
            'excerpt' => ['required', 'string', 'max:500'],
            'content' => ['nullable', 'required_if:type,article', 'string'],
            'featured_image' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
            'featured_image_alt' => ['nullable', 'string', 'max:255'],
            'status' => ['required', 'in:draft,published'],
            'published_at' => ['nullable', 'date'],
            'meta_title' => ['nullable', 'string', 'max:255'],
            'meta_description' => ['nullable', 'string', 'max:500'],
            'canonical_url' => ['nullable', 'url', 'max:500'],
            'robots' => ['nullable', Rule::in(['index,follow', 'noindex,nofollow', 'noindex,follow'])],
            'og_title' => ['nullable', 'string', 'max:255'],
            // TopList items
            'items' => ['required_if:type,toplist', 'nullable', 'array', 'min:1', 'max:20'],
            'items.*.rank' => ['required_with:items', 'integer', 'min:1', 'max:20'],
            'items.*.title' => ['required_with:items', 'string', 'max:255'],
            'items.*.image_url' => ['nullable', 'string', 'max:500'],
            'items.*.external_url' => ['nullable', 'url', 'max:500'],
            'items.*.description' => ['nullable', 'string', 'max:1000'],
            'items_images' => ['nullable', 'array'],
            'items_images.*' => ['nullable', 'file', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ];
    }
}
