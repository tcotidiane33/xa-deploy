@extends('layouts.admin')

@section('title', 'Activités')

@section('content')
    <div class="main-content">
        <div class="container mx-auto px-4 py-8">
            <h2 class="text-2xl font-bold mb-4">Activités</h2>

            <div class="mb-4">
                <a href="{{ route('admin.telescope') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Voir Telescope
                </a>
                <span class="p-2"> </span>
                <a href="{{ route('admin.clockwork') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Voir Clockwork
                </a>
            </div>

            <table class="min-w-full divide-y divide-gray-200">
                <thead>
                    <tr>
                        <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                        <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        <th scope="col" class="px-6 py-3 bg-gray-50 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Détails</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    @foreach ($activities as $activity)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $activity->created_at }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $activity->user->name ?? 'N/A' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $activity->event }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">{{ $activity->auditable_type }} (ID: {{ $activity->auditable_id }})</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <div class="mt-4">
                {{ $activities->links() }}
            </div>
        </div>
    </div>
@endsection
