<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasAutoSlug
{
    /**
     * Generate a unique slug from the name when none is provided.
     */
    protected static function bootHasAutoSlug(): void
    {
        static::saving(function ($model): void {
            if (blank($model->slug)) {
                $model->slug = $model->makeUniqueSlug($model->name);
            }
        });
    }

    /**
     * Build a slug from the given name and make sure it is unique.
     */
    protected function makeUniqueSlug(string $name): string
    {
        $base = Str::slug($name) ?: 'item';

        $slug = $base;
        $suffix = 2;

        while ($this->slugExists($slug)) {
            $slug = "{$base}-{$suffix}";
            $suffix++;
        }

        return $slug;
    }

    /**
     * Determine whether another record already uses the given slug.
     */
    protected function slugExists(string $slug): bool
    {
        $query = static::query()->where('slug', $slug);

        if ($this->exists) {
            $query->whereKeyNot($this->getKey());
        }

        return $query->exists();
    }
}
