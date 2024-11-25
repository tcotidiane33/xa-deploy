@extends('layouts.admin')

@section('title', 'Tableau de Bord')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Tableau de Bord</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
        <!-- Total Rôles -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Total Rôles</h3>
            <p class="text-2xl font-bold">{{ $totalRoles }}</p>
        </div>

        <!-- Total Permissions -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Total Permissions</h3>
            <p class="text-2xl font-bold">{{ $totalPermissions }}</p>
        </div>

        <!-- Total Utilisateurs -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Total Utilisateurs</h3>
            <p class="text-2xl font-bold">{{ $totalUsers }}</p>
        </div>

        <!-- Total Clients -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold mb-4">Total Clients</h3>
            <p class="text-2xl font-bold">{{ $totalClients }}</p>
        </div>
    </div>

    <!-- Derniers Rôles -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-lg font-semibold mb-4">Derniers Rôles</h3>
        <ul>
            @foreach ($recentRoles as $role)
                <li>{{ $role->name }}</li>
            @endforeach
        </ul>
    </div>

    <!-- Dernières Permissions -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-lg font-semibold mb-4">Dernières Permissions</h3>
        <ul>
            @foreach ($recentPermissions as $permission)
                <li>{{ $permission->name }}</li>
            @endforeach
        </ul>
    </div>

    <!-- Derniers Utilisateurs -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-lg font-semibold mb-4">Derniers Utilisateurs</h3>
        <ul>
            @foreach ($recentUsers as $user)
                <li>{{ $user->name }}</li>
            @endforeach
        </ul>
    </div>

    <!-- Derniers Clients -->
    <div class="bg-white rounded-lg shadow p-6 mb-8">
        <h3 class="text-lg font-semibold mb-4">Derniers Clients</h3>
        <ul>
            @foreach ($recentClients as $client)
                <li>{{ $client->name }}</li>
            @endforeach
        </ul>
    </div>
</div>
@endsection
