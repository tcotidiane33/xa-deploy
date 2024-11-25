@extends('layouts.admin')

@section('content')
    <div class="main-content">

        <div class="main-page">
            <div class="container">
                <div class="row">
                    </br></br>
                </div>
                <div class="breadcrumb">

                    <h1>Détails de la Convention Collective</h1>
                </div>

                <div class="card">
                    <div class="card-body">
                        <h5 class="card-title text-gray-900 dark:text-white text-3xl font-extrabold mb-2">
                            {{ $conventionCollective->name }}</h5>
                        <p class="card-text text-lg font-normal text-gray-500 dark:text-gray-400 mb-4">
                            N° IDCC: {{ $conventionCollective->idcc }}</p>
                        {{-- <p class="card-text text-lg font-normal text-gray-500 dark:text-gray-400 mb-4">
                            {{ $conventionCollective->description }}</p> --}}
                    </div>
                </div>

                <a href="{{ route('convention-collectives.edit', $conventionCollective) }}"
                    class="btn btn-primary mt-3">Modifier</a>
                <a href="{{ route('convention-collectives.index') }}" class="btn btn-danger  mt-3 bg-green-500">Retour à la
                    liste</a>
            </div>
        </div>
    </div>
@endsection