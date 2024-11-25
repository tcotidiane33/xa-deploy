<div class="modal fade" id="transfertMasseModal" tabindex="-1" role="dialog" aria-labelledby="transfertMasseModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <form action="{{ route('admin.gestionnaire-client.transfert-masse') }}" method="POST">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title" id="transfertMasseModalLabel">Transfert en masse</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="form-group">
                        <label for="ancien_gestionnaire_id">Ancien Gestionnaire</label>
                        <select name="ancien_gestionnaire_id" id="ancien_gestionnaire_id" class="form-control" required>
                            @foreach ($gestionnaires as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="nouveau_gestionnaire_id">Nouveau Gestionnaire</label>
                        <select name="nouveau_gestionnaire_id" id="nouveau_gestionnaire_id" class="form-control" required>
                            @foreach ($gestionnaires as $id => $name)
                                <option value="{{ $id }}">{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="clients">Clients à transférer</label>
                        <select name="clients[]" id="clients" class="form-control" multiple required>
                            @foreach ($clients as $client)
                                <option value="{{ $client->id }}">{{ $client->name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <div class="modal-footer">

                    <button type="button" class="text-white bg-gradient-to-r mb-3 from-pink-400 via-pink-500 to-pink-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-pink-300 dark:focus:ring-green-800 shadow-lg shadow-green-500/50 dark:shadow-lg dark:shadow-green-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 m-3" data-dismiss="modal">Fermer</button>
                    <button type="submit" class="text-white bg-gradient-to-r mb-3 from-cyan-400 via-cyan-500 to-cyan-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-cyan-300 dark:focus:ring-cyan-800 shadow-lg shadow-cyan-500/50 dark:shadow-lg dark:shadow-cyan-800/80 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 m-3">Transférer</button>
                </div>
            </form>
        </div>
    </div>
</div>