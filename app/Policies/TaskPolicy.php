<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Task;

class  TaskPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Task $task): bool
    {
        return $user->role == 'employee' ? $task->assigned_to === $user->user_id :  $task->manager_id === $user->user_id;;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user,  Task $task): bool
    {
        return  $user->role == 'employee' ? $task->assigned_to === $user->user_id : $task->manager_id === $user->user_id;
    }

    /**
     * Determine whether the user can delete the model.
     */

    public function delete(User $user, Task $task): bool
    {
        return $task->manager_id === $user->user_id;
    }

    public function restore(User $user, Task $task): bool
    {
        return $task->manager_id === $user->user_id;
    }
}
