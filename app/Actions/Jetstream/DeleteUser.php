<?php

namespace App\Actions\Jetstream;

use Laravel\Jetstream\Contracts\DeletesUsers;

class DeleteUser implements DeletesUsers
{
    public function delete($user)
    {
        if (demo()) {
            return back()->with('error', 'This feature is disabled on demo');
        }

        $user->deleteProfilePhoto();
        $user->tokens->each->delete();
        $user->delete();

        log_activity(__('User has deleted account.'), $user, $user, 'Profile');
    }
}
