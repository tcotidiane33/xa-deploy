@extends('layouts.main')

@section('content')
<div class="container mx-auto p-4 pt-6 md:p-6">
    <h2 class="text-2xl font-bold mb-4">{{ __('Reset Password') }}</h2>
    <div class="bg-white rounded-lg shadow-md p-4">
        @include('components.validation-form', [
            'action' => route('password.store'),
            'fields' => [
                [
                    'type' => 'hidden',
                    'name' => 'token',
                    'value' => $request->route('token')
                ],
                [
                    'type' => 'email',
                    'name' => 'email',
                    'label' => __('Email'),
                    'value' => old('email', $request->email),
                    'required' => true,
                    'autofocus' => true,
                    'autocomplete' => 'username'
                ],
                [
                    'type' => 'password',
                    'name' => 'password',
                    'label' => __('Password'),
                    'required' => true,
                    'autocomplete' => 'new-password'
                ],
                [
                    'type' => 'password',
                    'name' => 'password_confirmation',
                    'label' => __('Confirm Password'),
                    'required' => true,
                    'autocomplete' => 'new-password'
                ]
            ],
            'submit_text' => __('Reset Password')
        ])
    </div>
</div>
@endsection