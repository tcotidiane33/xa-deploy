@extends('layouts.admin')

@section('content')
    <div class="main-content">
        <div class="main-page">
            <div class="container">
                <div class="row">
                </br></br>
                </div>
                <div class="breadcrumb">
                    <h1>Ajouter une nouvelle Convention Collective</h1>
                </div>

                <form action="{{ route('convention-collectives.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label for="idcc">N° IDCC</label>
                        <input type="text" class="form-control" id="idcc" name="idcc" required pattern="\d{4}" title="Veuillez entrer exactement 4 chiffres">
                    </div>
                    <div class="form-group">
                        <label for="name">Nom</label>
                        <input type="text" class="form-control" id="name" name="name" required>
                    </div>
                    {{-- <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div> --}}
                    <button type="submit" class="text-white bg-gradient-to-r from-green-400 via-green-500 to-green-600 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">Ajouter</button>
                </form>
            </div>
        </div>
    </div>
@endsection