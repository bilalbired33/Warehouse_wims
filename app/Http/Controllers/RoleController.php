<?php

namespace App\Http\Controllers;

use App\Models\Role;
use Inertia\Inertia;
use Illuminate\Http\Request;
use App\Http\Requests\RoleRequest;
use App\Http\Resources\RoleResource;
use App\Http\Resources\RoleCollection;

class RoleController extends Controller
{
    public function create()
    {
        return Inertia::render('Role/Form');
    }

    public function destroy(Role $role)
    {
        if ($role->del()) {
            return redirect()->route('roles.index')->with('message', __choice('action_text', ['record' => 'Role', 'action' => 'deleted']));
        }

        return back()->with('error', __('The record can not be deleted.'));
    }

    public function destroyPermanently(Role $role)
    {
        if ($role->delP()) {
            return redirect()->route('roles.index')->with('message', __choice('action_text', ['record' => 'Role', 'action' => 'permanently deleted']));
        }

        return back()->with('error', __('The record can not be deleted.'));
    }

    public function edit(Role $role)
    {
        if ($role->name == 'Super Admin') {
            return redirect()->route('roles.index')->with('error', __('Super Admin role can not be modified.'));
        }

        return Inertia::render('Role/Form', ['edit' => new RoleResource($role)]);
    }

    public function index(Request $request)
    {
        return Inertia::render('Role/Index', [
            'filters' => $request->all('search', 'trashed'),
            'roles'   => new RoleCollection(
                Role::ofAccount()->orderBy('name')->filter($request->only('search', 'trashed'))->paginate()
            ),
        ]);
    }

    public function permissions(Request $request, Role $role)
    {
        $permissions = collect($request->all());
        $role->syncPermissions($permissions->except('_method')->flatten()->all());

        return back()->with('message', __('Role permissions has successfully saved.'));
    }

    public function restore(Role $role)
    {
        $role->restore();

        return back()->with('message', __choice('action_text', ['record' => 'Role', 'action' => 'restored']));
    }

    public function store(RoleRequest $request)
    {
        Role::create($request->validated());

        return redirect()->route('roles.index')->with('message', __choice('action_text', ['record' => 'Role', 'action' => 'created']));
    }

    public function update(RoleRequest $request, Role $role)
    {
        if ($role->name == 'Super Admin') {
            return redirect()->route('roles.index')->with('error', __('Super Admin role can not be modified.'));
        }

        $role->update($request->validated());

        return back()->with('message', __choice('action_text', ['record' => 'Role', 'action' => 'updated']));
    }
}
