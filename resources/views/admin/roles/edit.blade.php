@extends('layouts.admin')

@section('title', 'Modifier un Rôle')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Modifier un Rôle</h1>

    <form action="{{ route('admin.roles.update', $role->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="mb-4">
            <label for="name" class="block text-gray-700">Nom du Rôle</label>
            <input type="text" name="name" id="name" class="form-control" value="{{ $role->name }}" required>
        </div>
        <div class="mb-4">
            <label for="permissions" class="block text-gray-700">Permissions</label>
            <select name="permissions[]" id="permissions" class="form-control" multiple>
                @foreach($permissions as $permission)
                    <option value="{{ $permission->name }}" {{ $role->permissions->contains($permission) ? 'selected' : '' }}>{{ $permission->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Mettre à jour</button>
    </form>
</div>
@endsection