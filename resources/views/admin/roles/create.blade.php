@extends('layouts.admin')

@section('title', 'Créer un Rôle')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Créer un Rôle</h1>

    <form action="{{ route('admin.roles.store') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Nom du Rôle</label>
            <input type="text" name="name" id="name" class="form-control" required>
        </div>
        <div class="mb-4">
            <label for="permissions" class="block text-gray-700">Permissions</label>
            <select name="permissions[]" id="permissions" class="form-control" multiple>
                @foreach($permissions as $permission)
                    <option value="{{ $permission->name }}">{{ $permission->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Créer</button>
    </form>
</div>
@endsection