@extends('layouts.admin')

@section('title', 'Journal d\'Activités')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="bg-white rounded-lg shadow-lg p-6">
        <!-- En-tête -->
         <div class="">
                <a href="{{ route('admin.telescope') }}" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    Voir Telescope
                </a>
                <span class="p-2"> </span>
                <a href="{{ route('admin.clockwork') }}" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Voir Clockwork
                </a>
            </div>
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Journal d'Activités</h1>
                <p class="text-sm text-gray-600 mt-1">Suivi des modifications et actions du système</p>
            </div>
            
            <div class="flex items-center space-x-4">
                <div class="relative">
                    <input type="text" 
                           id="searchActivity" 
                           placeholder="Rechercher une activité..." 
                           class="w-64 pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    <div class="absolute left-3 top-2.5 text-gray-400">
                        <i class="fas fa-search"></i>
                    </div>
                </div>
                <select id="filterType" class="rounded-md border-gray-300 shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    <option value="">Tous les types</option>
                    <option value="created">Création</option>
                    <option value="updated">Modification</option>
                    <option value="deleted">Suppression</option>
                </select>
            </div>
        </div>

        <!-- Timeline des activités -->
        <div class="relative">
            <!-- Ligne verticale de la timeline -->
            <div class="absolute left-8 top-0 bottom-0 w-0.5 bg-gray-200"></div>

            <!-- Liste des activités -->
            <div class="space-y-6">
                @forelse ($activities as $activity)
                    <div class="relative pl-20 activity-item" 
                         data-type="{{ $activity->event }}"
                         data-user="{{ $activity->user->name ?? 'Système' }}"
                         data-model="{{ class_basename($activity->auditable_type) }}">
                        <!-- Indicateur de timeline -->
                        <div class="absolute left-6 -translate-x-1/2 w-6 h-6 rounded-full border-4 {{ 
                            $activity->event === 'created' ? 'bg-green-500 border-green-100' :
                            ($activity->event === 'updated' ? 'bg-blue-500 border-blue-100' :
                            'bg-red-500 border-red-100') 
                        }}"></div>

                        <!-- Contenu de l'activité -->
                        <div class="bg-gray-50 rounded-lg p-4 hover:bg-gray-100 transition-colors duration-200">
                            <div class="flex justify-between items-start mb-2">
                                <div>
                                    <h3 class="text-sm font-semibold text-gray-900">
                                        {{ $activity->user->name ?? 'Système' }}
                                        <span class="text-gray-600">a 
                                            @switch($activity->event)
                                                @case('created')
                                                    créé
                                                    @break
                                                @case('updated')
                                                    modifié
                                                    @break
                                                @case('deleted')
                                                    supprimé
                                                    @break
                                            @endswitch
                                            un(e) {{ class_basename($activity->auditable_type) }}
                                        </span>
                                    </h3>
                                    <p class="text-xs text-gray-500">
                                        <i class="far fa-clock mr-1"></i>
                                        {{ $activity->created_at->format('d/m/Y H:i') }}
                                        ({{ $activity->created_at->diffForHumans() }})
                                    </p>
                                </div>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ 
                                    $activity->event === 'created' ? 'bg-green-100 text-green-800' :
                                    ($activity->event === 'updated' ? 'bg-blue-100 text-blue-800' :
                                    'bg-red-100 text-red-800') 
                                }}">
                                    {{ ucfirst($activity->event) }}
                                </span>
                            </div>

                            @if(!empty($activity->old_values) || !empty($activity->new_values))
                                <div class="mt-3 text-sm">
                                    <button onclick="toggleChanges('{{ $activity->id }}')" 
                                            class="text-indigo-600 hover:text-indigo-800 font-medium">
                                        <i class="fas fa-code-branch mr-1"></i>
                                        Voir les modifications
                                    </button>
                                    <div id="changes-{{ $activity->id }}" class="hidden mt-2">
                                        <div class="grid grid-cols-2 gap-4 bg-white p-3 rounded border">
                                            @if(!empty($activity->old_values))
                                                <div>
                                                    <h4 class="font-medium text-gray-700 mb-2">Anciennes valeurs</h4>
                                                    @foreach($activity->old_values as $field => $value)
                                                        <div class="text-sm">
                                                            <span class="font-medium">{{ $field }}:</span>
                                                            <span class="text-red-600">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                            @if(!empty($activity->new_values))
                                                <div>
                                                    <h4 class="font-medium text-gray-700 mb-2">Nouvelles valeurs</h4>
                                                    @foreach($activity->new_values as $field => $value)
                                                        <div class="text-sm">
                                                            <span class="font-medium">{{ $field }}:</span>
                                                            <span class="text-green-600">{{ is_array($value) ? json_encode($value) : $value }}</span>
                                                        </div>
                                                    @endforeach
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="text-center py-12">
                        <div class="text-gray-400 mb-2">
                            <i class="fas fa-history text-4xl"></i>
                        </div>
                        <h3 class="text-lg font-medium text-gray-900">Aucune activité</h3>
                        <p class="text-gray-500">Les activités apparaîtront ici une fois que des actions seront effectuées.</p>
                    </div>
                @endforelse
            </div>
        </div>

        <!-- Pagination -->
        <div class="mt-6">
            {{ $activities->links() }}
        </div>
    </div>

    <!-- Statistiques -->
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mt-6">
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-green-100 text-green-600">
                    <i class="fas fa-plus"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Créations</h3>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $activities->where('event', 'created')->count() }}
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-blue-100 text-blue-600">
                    <i class="fas fa-edit"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Modifications</h3>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $activities->where('event', 'updated')->count() }}
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-red-100 text-red-600">
                    <i class="fas fa-trash"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Suppressions</h3>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $activities->where('event', 'deleted')->count() }}
                    </p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-lg shadow p-6">
            <div class="flex items-center">
                <div class="p-3 rounded-full bg-purple-100 text-purple-600">
                    <i class="fas fa-users"></i>
                </div>
                <div class="ml-4">
                    <h3 class="text-sm font-medium text-gray-500">Utilisateurs actifs</h3>
                    <p class="text-lg font-semibold text-gray-900">
                        {{ $activities->pluck('user_id')->unique()->count() }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function toggleChanges(id) {
    const changesDiv = document.getElementById(`changes-${id}`);
    changesDiv.classList.toggle('hidden');
}

document.addEventListener('DOMContentLoaded', function() {
    const searchInput = document.getElementById('searchActivity');
    const filterType = document.getElementById('filterType');
    const activityItems = document.querySelectorAll('.activity-item');

    function filterActivities() {
        const searchTerm = searchInput.value.toLowerCase();
        const selectedType = filterType.value;

        activityItems.forEach(item => {
            const type = item.dataset.type;
            const user = item.dataset.user.toLowerCase();
            const model = item.dataset.model.toLowerCase();
            const matchesSearch = user.includes(searchTerm) || model.includes(searchTerm);
            const matchesType = !selectedType || type === selectedType;

            item.style.display = matchesSearch && matchesType ? '' : 'none';
        });
    }

    searchInput.addEventListener('input', filterActivities);
    filterType.addEventListener('change', filterActivities);
});
</script>
@endpush

@endsection
