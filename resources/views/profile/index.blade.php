@extends('layouts.admin')

@section('title', 'Admin Traitements des paies')

@section('content')
    <div class="main-content">
        <div class="main-page">
            <div class="row">
                <br>
                <br>
            </div>
            <div class="row">
                <div class="container">
                    <div class="row justify-content-center">
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-header">{{ __('Profile') }}</div>

                                <div class="card-body">
                                    <div class="text-center mb-4">
                                        <img src="{{ $profile->avatar ? asset('storage/' . $profile->avatar) : asset('default-avatar.png') }}"
                                            alt="Avatar" class="rounded-circle" width="150">
                                    </div>
                                    <button class="btn btn-secondary dropdown-toggle" type="button" id="dropdownMenuButton"
                                        data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                                        Notifications <span
                                            class="badge badge-light">{{ auth()->user()->unreadNotifications->count() }}</span>
                                    </button>
                                    <h2>{{ $user->name }}</h2>
                                    <p><strong>Email:</strong> {{ $user->email }}</p>
                                    <p><strong>Phone:</strong> {{ $profile->phone ?? 'Not provided' }}</p>
                                    <p><strong>Bio:</strong> {{ $profile->bio ?? 'No bio available' }}</p>
                                    <p><strong>Birth Date:</strong> {{ $profile->birth_date ?? 'Not provided' }}</p>
                                    <p><strong>Address:</strong> {{ $profile->address ?? 'Not provided' }}</p>
                                    <p><strong>City:</strong> {{ $profile->city ?? 'Not provided' }}</p>
                                    <p><strong>Country:</strong> {{ $profile->country ?? 'Not provided' }}</p>

                                    <a href="{{ route('profile.edit') }}" class="btn btn-primary">Edit Profile</a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
