@extends('layouts.admin')

@section('title', 'Tableau de Bord')

@section('content')
<div class="container mx-auto px-4 py-8">
    <h1 class="text-3xl font-bold mb-6">Tableau de Bord</h1>

    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Total Rôles -->
        <div class="bg-white rounded-lg shadow p-6 flex items-center">
            <div class="mr-4">
                <svg class="w-12 h-12 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 1 1-8 0 4 4 0 0 1 8 0zM12 14v7m-4-4h8" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-2">Total Rôles</h3>
                <p class="text-2xl font-bold">{{ $totalRoles }}</p>
            </div>
        </div>

        <!-- Total Permissions -->
        <div class="bg-white rounded-lg shadow p-6 flex items-center">
            <div class="mr-4">
                <svg class="w-12 h-12 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m-7 4h14a2 2 0 0 0 2-2V7a2 2 0 0 0-2-2H5a2 2 0 0 0-2 2v10a2 2 0 0 0 2 2z" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-2">Total Permissions</h3>
                <p class="text-2xl font-bold">{{ $totalPermissions }}</p>
            </div>
        </div>

        <!-- Total Utilisateurs -->
        <div class="bg-white rounded-lg shadow p-6 flex items-center">
            <div class="mr-4">
                <svg class="w-12 h-12 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5.121 17.804A3.001 3.001 0 0 1 5 15V5a3 3 0 0 1 3-3h8a3 3 0 0 1 3 3v10a3 3 0 0 1-.121.804M15 21h-6m6 0a3 3 0 0 1-6 0m6 0H9" />
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-2">Total Utilisateurs</h3>
                <p class="text-2xl font-bold">{{ $totalUsers }}</p>
            </div>
        </div>

        <!-- Total Clients -->
        <div class="bg-white rounded-lg shadow p-6 flex items-center">
            <div class="mr-4">
                <svg class="w-12 h-12 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 11c0 1.104-.896 2-2 2s-2-.896-2-2 .896-2 2-2 2 .896 2 2zM12 11c0 1.104.896 2 2 2s2-.896 2-2-.896-2-2-2-2 .896-2 2zM12 11v10m0-10c0-1.104-.896-2-2-2s-2 .896-2 2 .896 2 2 2 2-.896 2-2zM12 11c0-1.104.896-2 2-2s2 .896 2 2-.896 2-2 2-2-.896-2-2z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-2">Total Clients</h3>
                <p class="text-2xl font-bold">{{ $totalClients }}</p>
            </div>
        </div>
    </div>

</div>
@endsection
