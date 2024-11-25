@extends('layouts.main')

@section('content')
<div class="container mx-auto p-4 pt-6 md:p-6">
    <h2 class="text-2xl font-bold mb-4">{{ __('Register') }}</h2>
    <div class="bg-white rounded-lg shadow-md p-4">
        @include('components.validation-form', [
            'action' => route('register'),
            'fields' => [
                [
                    'type' => 'text',
                    'name' => 'name',
                    'label' => __('Name'),
                    'placeholder' => 'Enter Your Name',
                    'required' => true,
                    'autofocus' => true,
                    'autocomplete' => 'name'
                ],
                [
                    'type' => 'email',
                    'name' => 'email',
                    'label' => __('Email'),
                    'placeholder' => 'Enter Your Email',
                    'required' => true,
                    'autocomplete' => 'username'
                ],
                [
                    'type' => 'password',
                    'name' => 'password',
                    'label' => __('Password'),
                    'placeholder' => 'Enter Your Password',
                    'required' => true,
                    'autocomplete' => 'new-password'
                ],
                [
                    'type' => 'password',
                    'name' => 'password_confirmation',
                    'label' => __('Confirm Password'),
                    'placeholder' => 'Confirm Your Password',
                    'required' => true,
                    'autocomplete' => 'new-password'
                ]
            ],
            'submit_text' => __('Register')
        ])

        <div class="text-sm text-gray-500 mb-4">
            {{ __('Already registered?') }}
            <a class="text-blue-600 hover:text-blue-700" href="{{ route('login') }}">
                {{ __('Login') }}
            </a>
        </div>
    </div>
</div>
@endsection