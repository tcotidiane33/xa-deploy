<form action="{{ route('admin.gestionnaire-client.index') }}" method="GET" class="mb-6">
    <div class="flex flex-wrap -mx-3 mb-2">
        <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="client_id">
                Client
            </label>
            <select name="client_id" id="client_id" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                <option value="">Tous les clients</option>
                @foreach ($clients as $id => $name)
                    <option value="{{ $id }}" {{ request('client_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="gestionnaire_id">
                Gestionnaire
            </label>
            <select name="gestionnaire_id" id="gestionnaire_id" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                <option value="">Tous les gestionnaires</option>
                @foreach ($gestionnaires as $id => $name)
                    <option value="{{ $id }}" {{ request('gestionnaire_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
        </div>
        <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0">
            <label class="block uppercase tracking-wide text-gray-700 text-xs font-bold mb-2" for="is_principal">
                Type de relation
            </label>
            <select name="is_principal" id="is_principal" class="block appearance-none w-full bg-gray-200 border border-gray-200 text-gray-700 py-3 px-4 pr-8 rounded leading-tight focus:outline-none focus:bg-white focus:border-gray-500">
                <option value="">Tous</option>
                <option value="1" {{ request('is_principal') == '1' ? 'selected' : '' }}>Principal</option>
                <option value="0" {{ request('is_principal') == '0' ? 'selected' : '' }}>Secondaire</option>
            </select>
        </div>
        <div class="w-full md:w-1/4 px-3 mb-6 md:mb-0 flex items-end">
            <button type="submit" class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                Filtrer
            </button>
            <a href="{{ route('admin.gestionnaire-client.index') }}" class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded ml-2">
                Réinitialiser
            </a>
        </div>
    </div>
</form>