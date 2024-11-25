@extends('layouts.admin')

@section('title', 'Créer une Permission')

@section('content')
    <div class="main-content">
        <div class="container mx-auto px-4 py-8">
            <h2 class="text-2xl font-bold mb-4">Créer une Permission</h2>

            <form method="POST" action="{{ route('admin.permissions.store') }}">
                @csrf

                <div class="mb-4">
                    <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Nom:</label>
                    <input type="text" name="name" id="name" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" value="{{ old('name') }}" required>
                </div>

                <div class="flex items-center justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Créer
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection
