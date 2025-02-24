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
            // Paramètres généraux
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string',
            'company_name' => 'required|string|max:255',
            'company_address' => 'required|string',
            'company_phone' => 'required|string',
            'company_email' => 'required|email',
            
            // Paramètres système
            'maintenance_mode' => 'boolean',
            'default_user_role' => 'required|exists:roles,id',
            'items_per_page' => 'required|integer|min:5|max:100',
            'allow_user_registration' => 'boolean',
            'enable_2fa' => 'boolean',
            'session_lifetime' => 'required|integer|min:1|max:1440',
            
            // Paramètres d'interface
            'theme' => 'required|string|in:light,dark,auto',
            'language' => 'required|string|in:fr,en',
            'timezone' => 'required|string',
            'date_format' => 'required|string',
            
            // Paramètres de notification
            'notifications_enabled' => 'boolean',
            'email_notifications' => 'boolean',
            'push_notifications' => 'boolean',
            
            // Paramètres de sécurité
            'password_expiry_days' => 'required|integer|min:0',
            'max_login_attempts' => 'required|integer|min:1',
            'password_min_length' => 'required|integer|min:8',
            'require_password_special_chars' => 'boolean',
            
            // Paramètres métier
            'default_periode_paie' => 'nullable|exists:periodes_paie,id',
            'enable_client_portal' => 'boolean',
            'enable_document_validation' => 'boolean',
            'default_document_retention_days' => 'required|integer|min:1',
        ]);

        try {
            foreach ($validatedData as $key => $value) {
                Setting::updateOrCreate(
                    ['key' => $key],
                    ['value' => $value]
                );
            }

            // Vider le cache des paramètres
            cache()->forget('settings');

            return redirect()
                ->route('admin.settings.index')
                ->with('success', 'Les paramètres ont été mis à jour avec succès.');
        } catch (\Exception $e) {
            return redirect()
                ->back()
                ->withInput()
                ->with('error', 'Une erreur est survenue lors de la mise à jour des paramètres : ' . $e->getMessage());
        }
    }
}
