<?php

namespace App\Services;

class SeoService
{
    protected array $data = [];

    public function set(array $seo): self
    {
        $this->data = array_merge([
            'title'       => config('blog.name'),
            'description' => config('blog.description'),
            'robots'      => 'index,follow',
            'canonical'   => request()->url(),
            'og_type'     => 'website',
            'og_image'    => asset(config('blog.og_image')),
            'schema'      => null,
        ], $seo);

        return $this;
    }

    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    public function all(): array
    {
        return $this->data;
    }
}