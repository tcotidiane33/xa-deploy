<?php

namespace App\Http\Controllers\Admin;

use App\Models\Role;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $settings = Setting::all()->pluck('value', 'key');
        $roles = Role::all()->pluck('name', 'id');
        return view('admin.settings.index', compact('settings', 'roles'));
    }

    public function update(Request $request)
    {
        $validatedData = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'maintenance_mode' => 'boolean',
            'default_user_role' => 'required|exists:roles,id',
            'items_per_page' => 'required|integer|min:5|max:100',
            'allow_user_registration' => 'boolean',
            'theme' => 'required|string|in:light,dark',
            'notifications' => 'boolean',
        ]);

        foreach ($validatedData as $key => $value) {
            Setting::updateOrCreate(
                ['key' => $key],
                ['value' => $value]
            );
        }

        return redirect()->route('admin.settings.index')->with('success', 'Settings updated successfully');
    }
}
