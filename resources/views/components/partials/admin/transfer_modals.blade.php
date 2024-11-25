@foreach ($clients as $client)
    <div class="modal fade" id="transferModal{{ $client->id }}" tabindex="-1" role="dialog" aria-labelledby="transferModalLabel{{ $client->id }}" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <form action="{{ route('admin.gestionnaire-client.transfer', $client->id) }}" method="POST">
                    @csrf
                    <div class="modal-header">
                        <h5 class="modal-title" id="transferModalLabel{{ $client->id }}">
                            Transférer le client {{ $client->name }}
                        </h5>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="new_gestionnaire_id">Nouveau gestionnaire</label>
                            <select name="new_gestionnaire_id" id="new_gestionnaire_id" class="form-control" required>
                                @foreach ($gestionnaires as $id => $name)
                                    @if ($id != $client->gestionnaire_principal_id)
                                        <option value="{{ $id }}">{{ $name }}</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Fermer</button>
                        <button type="submit" class="btn btn-primary">Transférer</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endforeach