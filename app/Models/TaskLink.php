<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TaskLink extends Model
{
   protected $fillable = [
        'task_id',
        'link',
   ];

   public function task()
   {
        $this->belongsTo(Task::class);
   }
}
