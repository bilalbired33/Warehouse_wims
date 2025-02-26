<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\User;
use Inertia\Inertia;
use App\Models\Warehouse;
use Illuminate\Http\Request;
use App\Http\Requests\UserRequest;
use App\Http\Resources\UserResource;
use App\Http\Resources\UserCollection;

class UserController extends Controller
{
    public function create()
    {
        return Inertia::render('User/Form', [
            'roles'      => Role::ofAccount()->get(),
            'warehouses' => Warehouse::ofAccount()->active()->get(),
        ]);
    }

    public function destroy(User $user)
    {
        if ($user->id == auth()->id()) {
            return back()->with('error', __('You should not delete your own account.'));
        }

        if ($user->del()) {
            return redirect()->route('users.index')->with('message', __choice('action_text', ['record' => 'User', 'action' => 'deleted']));
        }

        return back()->with('error', __('The record can not be deleted.'));
    }

    public function destroyPermanently(User $user)
    {
        if ($user->id == auth()->id()) {
            return back()->with('error', __('You should not delete your own account.'));
        }

        if ($user->delP()) {
            return redirect()->route('users.index')->with('message', __choice('action_text', ['record' => 'User', 'action' => 'deleted']));
        }

        return back()->with('error', __('The record can not be deleted.'));
    }

    public function disable2FA(User $user)
    {
        $user->forceFill(['two_factor_secret' => null, 'two_factor_recovery_codes' => null])->save();

        return back()->with('message', __choice('action_text', ['record' => 'Tow factor authentication', 'action' => 'disabled']));
    }

    public function edit(User $user)
    {
        return Inertia::render('User/Form', [
            'edit'       => new UserResource($user),
            'roles'      => Role::ofAccount()->get(),
            'warehouses' => Warehouse::ofAccount()->active()->get(),
        ]);
    }

    public function index(Request $request)
    {
        $filters = $request->all('search', 'role', 'trashed');

        return Inertia::render('User/Index', [
            'filters' => $filters,
            'roles'   => Role::ofAccount()->get(),
            'users'   => new UserCollection(User::ofAccount()->orderBy('name')->filter($filters)->paginate()),
        ]);
    }

    public function restore(User $user)
    {
        $user->restore();

        return back()->with('message', __choice('action_text', ['record' => 'User', 'action' => 'restored']));
    }

    public function store(UserRequest $request)
    {
        $user = User::create($request->validated());
        $user->assignRole($request->input('roles'));

        return redirect()->route('users.index')->with('message', __choice('action_text', ['record' => 'User', 'action' => 'created']));
    }

    public function update(UserRequest $request, User $user)
    {
        if ($user->id == auth()->id()) {
            return back()->with('error', __('You should not update your own account.'));
        }
        if (demo() && $user->id == 1) {
            return back()->with('error', 'This feature is disabled on demo');
        }

        $user->update($request->validated());
        $user->syncRoles($request->input('roles'));

        return back()->with('message', __choice('action_text', ['record' => 'User', 'action' => 'updated']));
    }
}
