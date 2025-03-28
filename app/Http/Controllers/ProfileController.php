<?php

namespace App\Http\Controllers;

use App\Models\Profile;
use App\Models\Setting;
use App\Models\Ticket;
use App\Models\Post;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Redirect;
use App\Http\Requests\ProfileUpdateRequest;

class ProfileController extends Controller
{

    public function index()
    {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile();
        $tickets = Ticket::latest()->take(5)->get();
        $posts = Post::latest()->take(5)->get();

        return view('profile.index', compact('user', 'profile', 'posts','tickets'));
    }

    public function edit(): View
    {
        $user = Auth::user();
        $profile = $user->profile ?? new Profile();
        return view('profile.edit', compact('user', 'profile'));
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $user = Auth::user();
        $user->fill($request->validated());

        if ($user->isDirty('email')) {
            $user->email_verified_at = null;
        }

        $user->save();

        $profile = $user->profile ?? new Profile(['user_id' => $user->id]);
        $profile->fill($request->validated());
        $profile->save();

        if ($request->hasFile('avatar')) {
            $avatarPath = $request->file('avatar')->store('avatars', 'public');
            $profile->avatar = $avatarPath;
            $profile->save();
        }

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
    public function search(Request $request)
    {
        $query = $request->input('query');
        // Implémentez votre logique de recherche ici
        // Par exemple, vous pouvez rechercher des utilisateurs ou des posts
        $results = []; // Remplacez ceci par vos résultats de recherche réels
        return response()->json($results);
    }

    public function profile()
    {
        $user = Auth::user();
        return view('profile.index', compact('user'));
    }



    public function updateAccount(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $user->id],
            // Ajoutez d'autres champs si nécessaire
        ]);

        $user->update($validated);

        return redirect()->route('profile.settings')->with('success', 'Account updated successfully');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
    public function updateSettings(Request $request)
    {
        $validatedData = $request->validate([
            'theme' => 'required|in:light,dark',
            'notifications' => 'boolean',
        ]);

        // Mettre à jour les paramètres de l'utilisateur
        $user = auth()->user();
        $user->settings()->updateOrCreate(
            ['user_id' => $user->id, 'key' => 'theme'],
            ['value' => $validatedData['theme']]
        );
        $user->settings()->updateOrCreate(
            ['user_id' => $user->id, 'key' => 'notifications'],
            ['value' => $validatedData['notifications'] ?? false]
        );

        return redirect()->route('profile.edit')->with('success', 'Settings updated successfully.');
    }

        public function settings()
    {
        $user = Auth::user();
        $settings = Setting::where('user_id', $user->id)->get()->pluck('value', 'key');
        $breadcrumbs = [
            ['link' => route('profile.index'), 'name' => 'Profile'],
            ['name' => 'Settings'],
        ];
        return view('profile.settings', compact('user', 'settings', 'breadcrumbs'));
    }
    // public function updateSettings(Request $request)
    // {
    //     $user = Auth::user();
    //     $validated = $request->validate([
    //         'theme' => 'required|in:light,dark',
    //         'notifications' => 'required|boolean',
    //         // Ajoutez d'autres champs de configuration ici
    //     ]);

    //     $settings = Setting::updateOrCreate(
    //         ['user_id' => $user->id],
    //         $validated
    //     );

    //     return redirect()->route('profile.settings')->with('success', 'Settings updated successfully');
    // }

}
