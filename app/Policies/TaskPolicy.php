<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\User;
use App\Models\Task;
use App\Models\Project;

class TaskPolicy
{
    use HandlesAuthorization;


    public function deleteCategory(User $user, Task $category)
    {
        return $category->project->manager->id === $user->id;
    }

}
