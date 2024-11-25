<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use Illuminate\Http\Request;
use App\Services\AccessControlService;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    protected $accessControlService;

    public function __construct(AccessControlService $accessControlService)
    {
        $this->middleware(['role:Admin']);
        $this->accessControlService = $accessControlService;
    }

    public function index()
    {
        $roles = Role::with('permissions')->get();
        $permissions = Permission::all();
        return view('admin.roles.index', compact('roles', 'permissions'));
    }

    public function create()
    {
        $permissions = Permission::all();
        return view('admin.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:roles,name',
            'permissions' => 'array'
        ]);

        $this->accessControlService->createRole($request->name, $request->permissions);

        return redirect()->route('admin.roles.index')->with('success', 'Rôle créé avec succès.');
    }

    public function edit(Role $role)
    {
        $permissions = Permission::all();
        return view('admin.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        $request->validate([
            'name' => "required|string|max:255|unique:roles,name,{$role->id}",
            'permissions' => 'array'
        ]);

        $this->accessControlService->updateRole($role->id, $request->name, $request->permissions);

        return redirect()->route('admin.roles.index')->with('success', 'Rôle mis à jour avec succès.');
    }

    public function destroy(Role $role)
    {
        $this->accessControlService->deleteRole($role->id);
        return redirect()->route('admin.roles.index')->with('success', 'Rôle supprimé avec succès.');
    }

    
    public function assignRoles()
    {
        $users = User::with('roles')->get();
        $roles = Role::all();
        return view('admin.roles.assign_role', compact('users', 'roles'));
    }

    public function storeAssignRoles(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        $user = User::findOrFail($request->user_id);
        $roles = Role::whereIn('id', $request->roles ?? [])->pluck('name');

        $user->syncRoles($roles);

        return redirect()->route('admin.roles.assign')->with('success', 'Rôles assignés avec succès.');
    }

    public function toggleUserStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();

        $status = $user->is_active ? 'activé' : 'désactivé';
        return redirect()->back()->with('success', "L'utilisateur a été $status avec succès.");
    }

    public function createPermission()
    {
        return view('admin.roles.create_permission');
    }

    public function storePermission(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255|unique:permissions,name'
        ]);

        $this->accessControlService->createPermission($request->name);

        return redirect()->route('admin.roles.index')
            ->with('success', 'Permission créée avec succès.');
    }
}
