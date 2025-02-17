@extends('layouts.admin')

@section('title', 'Liste des Conventions Collectives')

@push('styles')
<style>
    .cc-container {
        position: relative;
        overflow: hidden;
    }
    
    .cc-container::before {
        content: 'CONVENTIONS';
        position: absolute;
        top: 50%;
        left: 50%;
        transform: translate(-50%, -50%) rotate(-45deg);
        font-size: 6rem;
        font-weight: bold;
        color: rgba(229, 231, 235, 0.2);
        white-space: nowrap;
        pointer-events: none;
        z-index: 0;
    }
</style>
@endpush

@section('content')
    <div class="main-content">
        <div class="container mx-auto px-4 py-6">
            <div class="cc-container bg-gray-50 rounded-xl shadow-lg p-6 relative">
                <div class="border-b border-gray-200 pb-3 mb-4">
                    <div class="flex justify-between items-center">
                        <div>
                            <h2 class="text-xl font-bold text-gray-800">{{ __('Conventions Collectives') }}</h2>
                            <p class="text-xs text-gray-500 mt-1">Liste des conventions collectives disponibles</p>
                        </div>
                        <a href="{{ route('convention-collectives.create') }}"
                            class="bg-blue-500 hover:bg-blue-600 text-white text-sm font-medium py-2 px-4 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 flex items-center">
                            <i class="fas fa-plus-circle mr-2"></i>Nouvelle Convention
                        </a>
                    </div>
                </div>

                <div class="relative z-10">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-hashtag text-blue-400 mr-1"></i>N° IDCC
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-file-signature text-green-400 mr-1"></i>Nom
                                </th>
                                <th scope="col" class="px-4 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    <i class="fas fa-clock text-purple-400 mr-1"></i>Date de création
                                </th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            @foreach($conventionCollectives as $convention)
                                <tr class="hover:bg-gray-50 transition-colors duration-150">
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                        {{ $convention->idcc }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">
                                        {{ $convention->name }}
                                    </td>
                                    <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-500">
                                        {{ $convention->created_at->format('d/m/Y') }}
                                    </td>
                                    <td class="px-6 py-2 whitespace-nowrap text-right text-sm font-medium space-x-1">
                                        <a href="{{ route('convention-collectives.show', $convention->id) }}"
                                            class="inline-flex items-center px-2 py-1 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded transition-colors duration-150">
                                            <i class="fas fa-eye mr-1"></i> Voir
                                        </a>
                                        <a href="{{ route('convention-collectives.edit', $convention->id) }}"
                                            class="inline-flex items-center px-2 py-1 bg-blue-100 hover:bg-blue-200 text-blue-700 rounded transition-colors duration-150">
                                            <i class="fas fa-edit mr-1"></i> Modifier
                                        </a>
                                        <form action="{{ route('convention-collectives.destroy', $convention->id) }}" 
                                            method="POST" 
                                            class="inline-block"
                                            onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette convention ?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit"
                                                class="inline-flex items-center px-2 py-1 bg-red-100 hover:bg-red-200 text-red-700 rounded transition-colors duration-150">
                                                <i class="fas fa-trash-alt mr-1"></i> Supprimer
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>

                    @if($conventionCollectives->isEmpty())
                        <div class="text-center py-8">
                            <i class="fas fa-folder-open text-gray-400 text-4xl mb-3"></i>
                            <p class="text-gray-500">Aucune convention collective trouvée</p>
                        </div>
                    @endif

                    <!-- Pagination -->
                    <div class="mt-4">
                        {{ $conventionCollectives->links() }}
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection