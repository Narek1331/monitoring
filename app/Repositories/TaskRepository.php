<?php

namespace App\Repositories;

use App\Models\Task;

class TaskRepository
{
    public function getAllTasks()
    {
        return Task::where('sample',0)->all();
    }
}
