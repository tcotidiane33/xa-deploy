@extends('layouts.admin')

@section('content')
    <div class="main-content">
        <div class="container mx-auto px-4 py-8">
            <div class="row">
                <div class="col-md-12">
                    <h1 class="text-3xl font-bold text-gray-800 mb-6">Gestion des fichiers Excel</h1>

                    <!-- Formulaire pour télécharger un modèle -->
                    <div class="mb-4">
                        <h2 class="text-xl font-bold mb-4">Télécharger un modèle</h2>
                        <form action="{{ route('admin.files.downloadTemplate') }}" method="GET">
                            <div class="form-group">
                                <label for="table_name">Nom de la table</label>
                                <select name="table_name" id="table_name" class="form-control" required>
                                    @foreach($tableNames as $tableName)
                                        <option value="{{ $tableName }}">{{ $tableName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Télécharger le modèle</button>
                        </form>
                    </div>

                    <!-- Formulaire pour téléverser un fichier Excel -->
                    <div class="mb-4">
                        <h2 class="text-xl font-bold mb-4">Téléverser un fichier Excel</h2>
                        <form action="{{ route('admin.files.uploadExcel') }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="form-group">
                                <label for="table_name">Nom de la table</label>
                                <select name="table_name" id="table_name" class="form-control" required>
                                    @foreach($tableNames as $tableName)
                                        <option value="{{ $tableName }}">{{ $tableName }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="file">Fichier Excel</label>
                                <input type="file" name="file" id="file" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary mt-2">Téléverser</button>
                        </form>
                    </div>

                    <!-- Affichage des messages de succès ou d'erreur -->
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Affichage des informations sur le fichier téléversé -->
                    @if (session('file_info'))
                        <div class="alert alert-info">
                            <strong>Informations sur le fichier :</strong><br>
                            Nom du fichier : {{ session('file_info')['name'] }}<br>
                            Taille du fichier : {{ session('file_info')['size'] }} octets<br>
                            Nombre de lignes : {{ session('file_info')['rows'] }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection