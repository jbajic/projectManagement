<?php

namespace App\Policies;

use Illuminate\Auth\Access\HandlesAuthorization;
use App\Models\Project;
use App\Models\User;
use Auth;

class ProjectPolicy
{
    use HandlesAuthorization;


    public function destroy(User $user,Project $project)
    {
        return $user->id === $project->manager_id;
    }
}
