<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Spatie\Permission\Models\Permission;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function __construct()
    {
        $this->middleware('can:view permissions')->only('index');
        $this->middleware('can:create permissions')->only(['create', 'store']);
        $this->middleware('can:edit permissions')->only(['edit', 'update']);
        $this->middleware('can:delete permissions')->only('destroy');
    }
    public function index()
    {
        $permissions = Permission::all();
        return view('admin.permissions.index', compact('permissions'));
    }

    public function create()
    {
        return view('admin.permissions.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name',
        ]);

        Permission::create($request->only('name'));

        return redirect()->route('admin.permissions.index')->with('success', 'Permission créée avec succès.');
    }

    public function edit(Permission $permission)
    {
        return view('admin.permissions.edit', compact('permission'));
    }

    public function update(Request $request, Permission $permission)
    {
        $request->validate([
            'name' => 'required|unique:permissions,name,' . $permission->id,
        ]);

        $permission->update($request->only('name'));

        return redirect()->route('admin.permissions.index')->with('success', 'Permission mise à jour avec succès.');
    }

    public function destroy(Permission $permission)
    {
        $permission->delete();

        return redirect()->route('admin.permissions.index')->with('success', 'Permission supprimée avec succès.');
    }
}
