@extends('layouts.admin')

@section('title', 'Paramètres de l\'Application')

@section('content')
    <div class="container mx-auto p-4">
        <h1 class="text-3xl font-bold mb-6">Paramètres de l'Application</h1>
        <form method="POST" action="{{ route('admin.settings.update') }}" class="space-y-6">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="mb-4">
                    <label for="site_name" class="block text-sm font-medium text-gray-700">Nom du site</label>
                    <input type="text" name="site_name" id="site_name" value="{{ $settings['site_name'] ?? '' }}" required class="mt-1 block w-full py-1 px-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div class="mb-4">
                    <label for="site_description" class="block text-sm font-medium text-gray-700">Description du site</label>
                    <textarea name="site_description" id="site_description" class="mt-1 block w-full py-1 px-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ $settings['site_description'] ?? '' }}</textarea>
                </div>

                <div class="mb-4">
                    <label for="maintenance_mode" class="block text-sm font-medium text-gray-700">Mode Maintenance</label>
                    <div class="flex items-center">
                        <input type="checkbox" name="maintenance_mode" id="maintenance_mode" value="1" {{ $settings['maintenance_mode'] ?? false ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="maintenance_mode" class="ml-2 block text-sm text-gray-900">Activer le mode maintenance</label>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="default_user_role" class="block text-sm font-medium text-gray-700">Rôle utilisateur par défaut</label>
                    <select name="default_user_role" id="default_user_role" required class="mt-1 block w-full py-1 px-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        @foreach($roles as $id => $name)
                            <option value="{{ $id }}" {{ isset($settings['default_user_role']) && $settings['default_user_role'] == $id ? 'selected' : '' }}>{{ $name }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-4">
                    <label for="items_per_page" class="block text-sm font-medium text-gray-700">Articles par page</label>
                    <input type="number" name="items_per_page" id="items_per_page" value="{{ $settings['items_per_page'] ?? 10 }}" required min="5" max="100" class="mt-1 block w-full py-1 px-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div class="mb-4">
                    <label for="allow_user_registration" class="block text-sm font-medium text-gray-700">Autoriser l'inscription des utilisateurs</label>
                    <div class="flex items-center">
                        <input type="checkbox" name="allow_user_registration" id="allow_user_registration" value="1" {{ $settings['allow_user_registration'] ?? false ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="allow_user_registration" class="ml-2 block text-sm text-gray-900">Autoriser l'inscription des utilisateurs</label>
                    </div>
                </div>

                <div class="mb-4">
                    <label for="theme" class="block text-sm font-medium text-gray-700">Thème</label>
                    <select name="theme" id="theme" required class="mt-1 block w-full py-1 px-2 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="light" {{ $settings['theme'] == 'light' ? 'selected' : '' }}>Clair</option>
                        <option value="dark" {{ $settings['theme'] == 'dark' ? 'selected' : '' }}>Sombre</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label for="notifications" class="block text-sm font-medium text-gray-700">Notifications</label>
                    <div class="flex items-center">
                        <input type="checkbox" name="notifications" id="notifications" value="1" {{ $settings['notifications'] ?? false ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="notifications" class="ml-2 block text-sm text-gray-900">Activer les notifications</label>
                    </div>
                </div>
            </div>

            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Mettre à jour les paramètres</button>
        </form>
    </div>
@endsection
