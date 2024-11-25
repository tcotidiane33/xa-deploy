@extends('layouts.admin')

@section('title', 'Gestion des Rôles et Permissions')

@section('content')
    <div class="container mx-auto px-4 py-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6">Gestion des Rôles et Permissions</h1>

        <div class="mb-4">
            <a href="{{ route('admin.roles.create') }}" class="btn btn-primary mb-3">Créer un nouveau rôle</a>
            <a href="{{ route('admin.permissions.create') }}" class="btn btn-info mb-3">Créer une nouvelle permission</a>
            {{-- <a href="{{ route('admin.roles.assign') }}" class="btn btn-info mb-3">Assigner des rôles aux utilisateurs</a> --}}
        </div>

        <div class="grid gap-6 mb-6 md:grid-cols-1">
            <div class="form-grids widget-shadow" data-example-id="basic-forms">
                <div class="form-title">
                    <h4>Rôles :</h4>
                </div>
                <div class="form-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                    <th>Permissions</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $role->name }}</td>
                                        <td>
                                            @foreach ($role->permissions as $permission)
                                                <span class="label label-info">{{ $permission->name }}</span>
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="{{ route('admin.roles.edit', $role) }}"
                                                class="text-white bg-blue-700 hover:bg-blue-800 focus:ring-4 focus:ring-blue-300 font-medium rounded-lg text-sm px-2 py-2">Modifier</a>
                                            <form action="{{ route('admin.roles.destroy', $role) }}" method="POST"
                                                style="display:inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="text-white bg-red-700 hover:bg-red-800 focus:ring-4 focus:ring-red-300 font-medium rounded-lg text-sm px-2 py-2"
                                                    onclick="return confirm('Êtes-vous sûr de vouloir supprimer ce rôle ?')">Supprimer</button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
{{--
            <div class="form-grids row widget-shadow" data-example-id="basic-forms">
                <div class="form-title">
                    <h4>Permissions :</h4>
                </div>
                <div class="form-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Nom</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($permissions as $permission)
                                    <tr>
                                        <td>{{ $permission->name }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div> --}}
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .mb-3 {
            margin-bottom: 1rem;
        }

        .label {
            display: inline-block;
            padding: .2em .6em .3em;
            font-size: 75%;
            font-weight: 700;
            line-height: 1;
            color: #fff;
            text-align: center;
            white-space: nowrap;
            vertical-align: baseline;
            border-radius: .25em;
        }

        .label-info {
            background-color: #5bc0de;
        }
    </style>
@endpush
