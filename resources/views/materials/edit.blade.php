@extends('layouts.admin')

@section('title', 'Modifier le Matériau')

@section('content')
    <div class="main-content">
        <div class="container mx-auto px-4 py-8">
            <div class="row">
                <div class="container">
                    <form method="POST" action="{{ route('materials.update', $material->id) }}">
                        @csrf
                        @method('PUT')

                        <div class="mb-4">
                            <label for="client_id" class="block text-gray-700 text-sm font-bold mb-2">Client:</label>
                            <select name="client_id" id="client_id"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                                @foreach ($clients as $client)
                                    <option value="{{ $client->id }}"
                                        {{ $material->client_id == $client->id ? 'selected' : '' }}>{{ $client->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="title" class="block text-gray-700 text-sm font-bold mb-2">Titre:</label>
                            <input type="text" name="title" id="title"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                value="{{ old('title', $material->title) }}" required>
                        </div>

                        <div class="mb-4">
                            <label for="type" class="block text-gray-700 text-sm font-bold mb-2">Type:</label>
                            <select name="type" id="type"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                required>
                                <option value="autre" {{ old('type', $material->type) == 'autre' ? 'selected' : '' }}>Autre</option>
                                <option value="document" {{ old('type', $material->type) == 'document' ? 'selected' : '' }}>Document</option>
                                <option value="image" {{ old('type', $material->type) == 'image' ? 'selected' : '' }}>Image</option>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label for="content" class="block text-gray-700 text-sm font-bold mb-2">Contenu:</label>
                            <textarea name="content" id="content" rows="4"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('content', $material->content) }}</textarea>
                        </div>

                        <div class="mb-4">
                            <label for="content_url" class="block text-gray-700 text-sm font-bold mb-2">URL du contenu:</label>
                            <input type="url" name="content_url" id="content_url"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                value="{{ old('content_url', $material->content_url) }}">
                        </div>

                        <div class="mb-4">
                            <label for="field_name" class="block text-gray-700 text-sm font-bold mb-2">Nom du champ:</label>
                            <input type="text" name="field_name" id="field_name"
                                class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline"
                                value="{{ old('field_name', $material->field_name) }}">
                        </div>

                        <div class="flex items-center justify-between">
                            <button type="submit"
                                class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                Met à jour le Matériau !
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
