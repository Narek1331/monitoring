<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TechnicalSupport extends Model
{
      protected $fillable = [
        'email',
        'name',
        'subject',
        'message',
    ];
}
