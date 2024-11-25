@extends('layouts.admin')

@section('title', 'Permissions')

@section('content')
    <div class="main-content">
        <div class="container mx-auto px-4 py-8">
            <h2 class="text-2xl font-bold mb-4">Permissions</h2>

            <div class="mb-4">
                <a href="{{ route('admin.permissions.create') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Créer une Permission
                </a>
            </div>

            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                        <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($permissions as $permission)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $permission->name }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <a href="{{ route('admin.permissions.edit', $permission->id) }}" class="text-blue-500 hover:text-blue-700">Modifier</a>
                                <form action="{{ route('admin.permissions.destroy', $permission->id) }}" method="POST" style="display:inline-block;">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-500 hover:text-red-700">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@endsection
