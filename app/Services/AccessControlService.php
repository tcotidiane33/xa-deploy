<?php

namespace App\Services;

use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class AccessControlService
{
    public function createRole($name, $permissions = [])
    {
        $role = Role::create(['name' => $name, 'guard_name' => 'web']);
        $role->syncPermissions($permissions);
        return $role;
    }

    public function updateRole($roleId, $name, $permissions = [])
    {
        $role = Role::findOrFail($roleId);
        $role->update(['name' => $name]);
        $role->syncPermissions($permissions);
        return $role;
    }

    public function deleteRole($roleId)
    {
        $role = Role::findOrFail($roleId);
        $role->delete();
    }

    public function createPermission($name)
    {
        return Permission::create(['name' => $name, 'guard_name' => 'web']);
    }

    public function assignRoleToUser($userId, $roleName)
    {
        $user = User::findOrFail($userId);
        $user->assignRole($roleName);
    }

    public function removeRoleFromUser($userId, $roleName)
    {
        $user = User::findOrFail($userId);
        $user->removeRole($roleName);
    }

    public function givePermissionToRole($roleId, $permissionName)
    {
        $role = Role::findOrFail($roleId);
        $role->givePermissionTo($permissionName);
    }

    public function revokePermissionFromRole($roleId, $permissionName)
    {
        $role = Role::findOrFail($roleId);
        $role->revokePermissionTo($permissionName);
    }

    public function assignRole(Request $request, User $user)
    {
        $role = Role::findByName($request->role);
        $user->assignRole($role);

        return redirect()->back()->with('success', 'Rôle assigné avec succès.');
    }

    public function revokeRole(Request $request, User $user)
    {
        $role = Role::findByName($request->role);
        $user->removeRole($role);

        return redirect()->back()->with('success', 'Rôle révoqué avec succès.');
    }

    public function givePermission(Request $request, User $user)
    {
        $permission = Permission::findByName($request->permission);
        $user->givePermissionTo($permission);

        return redirect()->back()->with('success', 'Permission accordée avec succès.');
    }

    public function revokePermission(Request $request, User $user)
    {
        $permission = Permission::findByName($request->permission);
        $user->revokePermissionTo($permission);

        return redirect()->back()->with('success', 'Permission révoquée avec succès.');
    }
}