<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Traits\Slugify;
class VerificationMethod extends Model
{
    use Slugify;
    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'title',
        'short_title',
        'description',
        'order_by',
        'slug',
    ];

    protected static function boot()
    {
        parent::boot();
        static::bootSlugifyTrait();
    }
}

