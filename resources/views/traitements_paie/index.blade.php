@extends('layouts.admin')

@section('content')
<div class="container mx-auto px-4 py-8">
    <div class="breadcrumb">
        {{-- <a href="{{ route('traitements-paie.create') }}" class="text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Nouveau Traitement</a> --}}
    </div>
    <h1 class="text-3xl font-bold text-gray-800 mb-6">Liste des Traitements de Paie</h1>
    <div class="relative overflow-x-auto p-2 shadow-md sm:rounded-lg">
        <table id="fichesClientsTable" class="w-full text-sm text-left text-gray-500 dark:text-gray-400">
            <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                <tr>
                    <th scope="col" class="px-1 py-0">Client</th>
                    <th scope="col" class="px-1 py-0">Période de Paie</th>
                    <th scope="col" class="px-1 py-0">Réception Variables</th>
                    <th scope="col" class="px-1 py-0">Préparation BP</th>
                    <th scope="col" class="px-1 py-0">Validation BP Client</th>
                    <th scope="col" class="px-1 py-0">Préparation Envoie DSN</th>
                    <th scope="col" class="px-1 py-0">Accusés DSN</th>
                    <th scope="col" class="px-1 py-0">NB Bulletins</th>
                    <th scope="col" class="px-1 py-0">Maj Fiche Para</th>
                    <th scope="col" class="px-1 py-0">Actions</th>
                </tr>
            </thead>
            <tbody>
                @foreach ($fichesClients as $ficheClient)
                <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700">
                    <td class="border-purple-800 font-extrabold tracking-tight leading-none text-gray-900 dark:text-white p-0 m-0 text-center "><span class="bg-pink-100 text-pink-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-pink-900 dark:text-pink-300">{{ $ficheClient->client->name ?? 'N/A' }}</span></td>
                    <td class="border-purple-800 font-extrabold tracking-tight leading-none text-gray-900 dark:text-white p-0 m-0 text-center "><span class="bg-purple-100 text-purple-800 text-xs font-medium me-2 px-2.5 py-0.5 rounded dark:bg-purple-900 dark:text-purple-300">{{ $ficheClient->periodePaie->reference ?? 'N/A' }}</span></td>
                    <td class="border-purple-800 text-xl font-extrabold tracking-tight leading-none text-gray-900 md:text-xl lg:text-1xl dark:text-white p-0 m-0 text-center  {{ $ficheClient->reception_variables_file ? 'bg-green-100' : 'bg-gray-100' }}">{{ $ficheClient->reception_variables_file ?? 'N/A' }}</td>
                    <td class="border-purple-800 text-xl font-extrabold tracking-tight leading-none text-gray-900 md:text-xl lg:text-1xl dark:text-white p-0 m-0 text-center  {{ $ficheClient->preparation_bp_file ? 'bg-green-100' : 'bg-gray-100' }}">{{ $ficheClient->preparation_bp_file ?? 'N/A' }}</td>
                    <td class="border-purple-800 text-xl font-extrabold tracking-tight leading-none text-gray-900 md:text-xl lg:text-1xl dark:text-white p-0 m-0 text-center  {{ $ficheClient->validation_bp_client_file ? 'bg-green-100' : 'bg-gray-100' }}">{{ $ficheClient->validation_bp_client_file ?? 'N/A' }}</td>
                    <td class="border-purple-800 text-xl font-extrabold tracking-tight leading-none text-gray-900 md:text-xl lg:text-1xl dark:text-white p-0 m-0 text-center  {{ $ficheClient->preparation_envoie_dsn_file ? 'bg-green-100' : 'bg-gray-100' }}">{{ $ficheClient->preparation_envoie_dsn_file ?? 'N/A' }}</td>
                    <td class="border-purple-800 text-xl font-extrabold tracking-tight leading-none text-gray-900 md:text-xl lg:text-1xl dark:text-white p-0 m-0 text-center  {{ $ficheClient->accuses_dsn_file ? 'bg-green-100' : 'bg-gray-100' }}">{{ $ficheClient->accuses_dsn_file ?? 'N/A' }}</td>
                    <td class="border-purple-800 text-xl font-extrabold tracking-tight leading-none text-gray-900 md:text-xl lg:text-1xl dark:text-white p-0 m-0 text-center  {{ $ficheClient->nb_bulletins_file ? 'bg-green-100' : 'bg-gray-100' }}">{{ $ficheClient->nb_bulletins_file ?? 'N/A' }}</td>
                    <td class="border-purple-800 text-xl font-extrabold tracking-tight leading-none text-gray-900 md:text-xl lg:text-1xl dark:text-white p-0 m-0 text-center  {{ $ficheClient->maj_fiche_para_file ? 'bg-green-100' : 'bg-gray-100' }}">{{ $ficheClient->maj_fiche_para_file ?? 'N/A' }}</td>
                    <td class="p-0 m-0 flex space-x-2">
                        <button onclick="openPopup({{ $ficheClient->id }})" class="text-white bg-gradient-to-r from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 font-medium rounded-lg text-sm px-1 py-1 text-center ">Mettre à jour</button>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
        <div class="mt-4">
            {{ $fichesClients->links() }}
        </div>
    </div>
</div>

<!-- Popup de mise à jour -->
<div id="updatePopup" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden flex items-center justify-center">
    <div class="row">
        <br>
        <br>
    </div>
    <div class="relative p-3 border w-92 shadow-lg rounded-md bg-white">
        <div class="mt-6 ml-6">
            <h3 class="text-lg leading-6 font-medium text-gray-900">Ajouter les variables</h3>
            <form id="updateForm" method="POST" action="" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="fiche_client_id" id="fiche_client_id">
                <div class="grid gap-6 mb-6 md:grid-cols-2">
                    <div>
                        <div class="mt-1">
                            <label for="reception_variables_file" class="block text-sm font-medium text-gray-700">Fichier Réception variables</label>
                            <input type="file" name="reception_variables_file" id="reception_variables_file" class="form-control">
                        </div>
                        <div class="mt-1">
                            <label for="preparation_bp_file" class="block text-sm font-medium text-gray-700">Fichier Préparation BP</label>
                            <input type="file" name="preparation_bp_file" id="preparation_bp_file" class="form-control">
                        </div>
                        <div class="mt-1">
                            <label for="validation_bp_client_file" class="block text-sm font-medium text-gray-700">Fichier Validation BP client</label>
                            <input type="file" name="validation_bp_client_file" id="validation_bp_client_file" class="form-control">
                        </div>
                        <div class="mt-1">
                            <label for="preparation_envoie_dsn_file" class="block text-sm font-medium text-gray-700">Fichier Préparation et envoie DSN</label>
                            <input type="file" name="preparation_envoie_dsn_file" id="preparation_envoie_dsn_file" class="form-control">
                        </div>
                    </div>
                    <div>
                        <div class="mt-1">
                            <label for="accuses_dsn_file" class="block text-sm font-medium text-gray-700">Fichier Accusés DSN</label>
                            <input type="file" name="accuses_dsn_file" id="accuses_dsn_file" class="form-control">
                        </div>
                        <div class="mt-1">
                            <label for="nb_bulletins_file" class="block text-sm font-medium text-gray-700">Fichier NB Bulletins</label>
                            <input type="file" name="nb_bulletins_file" id="nb_bulletins_file" class="form-control">
                        </div>
                        <div class="mt-1">
                            <label for="maj_fiche_para_file" class="block text-sm font-medium text-gray-700">Fichier Maj fiche para</label>
                            <input type="file" name="maj_fiche_para_file" id="maj_fiche_para_file" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="mt-4 flex justify-between">
                    <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                        Enregistrer
                    </button>
                    <button type="button" onclick="closePopup()" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Annuler
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openPopup(ficheClientId) {
        if (ficheClientId === 'null') {
            alert('Aucune fiche client associée.');
            return;
        }

        fetch(`/fiches-clients/${ficheClientId}`)
            .then(response => response.json())
            .then(data => {
                document.getElementById('fiche_client_id').value = ficheClientId;
                document.getElementById('updateForm').action = `/traitements-paie/update-fiche-client/${ficheClientId}`;
                document.getElementById('updatePopup').classList.remove('hidden');
            });
    }

    function closePopup() {
        document.getElementById('updatePopup').classList.add('hidden');
    }
</script>
@endsection