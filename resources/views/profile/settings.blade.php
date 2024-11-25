@extends('layouts.admin')

@section('title', 'Admin Settings')

@section('content')
    <div class="main-content">
        <div class="main-page">
            <div class="row">
                <br>
                <br>
            </div>
            <div class="row">
                <div class="container mx-auto p-4">
                    <form method="POST" action="{{ route('profile.update-settings') }}" class="space-y-6">
                        @csrf
                        <div class="mb-4">
                            <label for="theme" class="block text-sm font-medium text-gray-700">Thème</label>
                            <select name="theme" id="theme" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="light" {{ $settings->where('key', 'theme')->first()->value == 'light' ? 'selected' : '' }}>Clair</option>
                                <option value="dark" {{ $settings->where('key', 'theme')->first()->value == 'dark' ? 'selected' : '' }}>Sombre</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label for="notifications" class="block text-sm font-medium text-gray-700">Notifications</label>
                            <div class="flex items-center">
                                <input type="checkbox" name="notifications" id="notifications" value="1" {{ $settings->where('key', 'notifications')->first()->value ? 'checked' : '' }} class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                <label for="notifications" class="ml-2 block text-sm text-gray-900">Activer les notifications</label>
                            </div>
                        </div>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">Mettre à jour les paramètres</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
