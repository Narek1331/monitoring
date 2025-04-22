<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Contact extends Model
{
    protected $fillable = [
        'type_id',
        'status',
        'user_id',
        'name',
        'email',
        'tg_verification_code',
        'phone',
        'http_url',
        'http_password',
    ];

    public function type()
    {
        return $this->belongsTo(ContactType::class);
    }
}
