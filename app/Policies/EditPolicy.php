<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EditPolicy
{
    use HandlesAuthorization;

    public function update(User $user, $model)
    {
        return $user->id === $model->user_id || (bool) $user->edit_all;
    }
}
