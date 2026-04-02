<?php

namespace App\Traits;

use Illuminate\Support\Facades\Storage;

trait SyncsContentImages
{
    private function extractLocalImageSrcs(string $html): array
    {
        if (empty($html)) {
            return [];
        }

        preg_match_all('/<img[^>]+src=["\']([^"\']+)["\'][^>]*>/i', $html, $matches);

        return array_values(
            array_filter($matches[1] ?? [], fn ($src) => str_contains($src, '/img/'))
        );
    }

    private function storagePathFromSrc(string $src): string
    {
        $path = parse_url($src, PHP_URL_PATH) ?? '';

        return ltrim(preg_replace('#^/img/#', '', $path), '/');
    }

    private function deleteOrphanedImages(array $oldSrcs, array $newSrcs): void
    {
        foreach (array_diff($oldSrcs, $newSrcs) as $src) {
            $path = $this->storagePathFromSrc($src);

            if ($path && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }
        }
    }
}
