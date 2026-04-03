<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class UpdateHomeSectionRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'container_id' => ['required', 'integer', 'exists:home_containers,id'],
            'title' => ['required', 'string', 'max:255'],
            'show_title' => ['nullable', 'boolean'],
            'show_view_all' => ['nullable', 'boolean'],
            'view_all_url' => ['nullable', 'string', 'max:500'],
            'post_type' => ['required', 'in:article,toplist'],
            'layout' => ['required', 'in:card,list'],
            'category_ids' => ['nullable', 'array'],
            'category_ids.*' => ['integer', 'exists:categories,id'],
            'order_by' => ['required', 'in:latest,oldest'],
            'limit' => ['required', 'integer', 'min:1', 'max:20'],
            'is_active' => ['nullable', 'boolean'],
            'accent_color' => ['nullable', 'string', 'regex:/^#[0-9a-fA-F]{6}$/'],
        ];
    }

    protected function prepareForValidation(): void
    {
        $this->merge([
            'show_title' => $this->boolean('show_title'),
            'show_view_all' => $this->boolean('show_view_all'),
            'is_active' => $this->boolean('is_active'),
        ]);
    }
}
