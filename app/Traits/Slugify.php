<?php

namespace App\Traits;

use Illuminate\Support\Str;

trait Slugify
{
    public static function bootSlugifyTrait()
    {
        static::creating(function ($model) {
            $model->generateSlug();
        });

        // static::updating(function ($model) {
        //     $model->generateSlug();
        // });
    }

    protected function generateSlug()
    {
        if (!empty($this->slug)) {
            return;
        }

        $source = $this->name ?? $this->title ?? null;

        if ($source) {
            $slug = Str::slug($source,'_');
            $this->slug = $this->generateUniqueSlug($slug);
        }
    }

    protected function generateUniqueSlug($baseSlug)
    {
        $slug = $baseSlug;
        $i = 1;

        while (static::where('slug', $slug)->where('id', '!=', $this->id)->exists()) {
            $slug = $baseSlug . '_' . $i++;
        }

        return $slug;
    }
}
