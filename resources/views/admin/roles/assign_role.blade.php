@extends('layouts.admin')

@section('title', 'Assigner des Rôles')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Assigner des Rôles</h1>

    <form action="{{ route('admin.roles.storeAssignRoles') }}" method="POST">
        @csrf
        <div class="mb-4">
            <label for="user_id" class="block text-gray-700">Utilisateur</label>
            <select name="user_id" id="user_id" class="form-control" required>
                @foreach($users as $user)
                    <option value="{{ $user->id }}">{{ $user->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="mb-4">
            <label for="roles" class="block text-gray-700">Rôles</label>
            <select name="roles[]" id="roles" class="form-control" multiple>
                @foreach($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>
        </div>
        <button type="submit" class="btn btn-primary">Assigner</button>
    </form>
</div>
@endsection