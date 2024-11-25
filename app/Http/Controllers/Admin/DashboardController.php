<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Client;
use App\Models\Role;
use App\Models\Permission;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware(['auth', 'role:Admin']);
    }

    public function index()
    {
        // Fetch total counts of roles, permissions, users, clients, etc.
        $totalRoles = Role::count();
        $totalPermissions = Permission::count();
        $totalUsers = User::count();
        $totalClients = Client::count();

        // Fetch recent interactions
        $recentRoles = Role::latest()->take(5)->get();
        $recentPermissions = Permission::latest()->take(5)->get();
        $recentUsers = User::latest()->take(5)->get();
        $recentClients = Client::latest()->take(5)->get();

        return view('admin.dashboard', compact(
            'totalRoles',
            'totalPermissions',
            'totalUsers',
            'totalClients',
            'recentRoles',
            'recentPermissions',
            'recentUsers',
            'recentClients'
        ));
    }
}
