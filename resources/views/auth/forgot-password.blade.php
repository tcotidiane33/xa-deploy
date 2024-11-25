@extends('layouts.main')

@section('content')
<div class="container mx-auto p-4 pt-6 md:p-6">
    <h2 class="text-2xl font-bold mb-4">{{ __('Forgot Password') }}</h2>
    <div class="bg-white rounded-lg shadow-md p-4">
        <div class="mb-4">
            {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        @include('components.auth-session-status', ['status' => session('status')])

        @include('components.validation-form', [
            'action' => route('password.email'),
            'fields' => [
                [
                    'type' => 'email',
                    'name' => 'email',
                    'label' => __('Email'),
                    'placeholder' => 'Enter Your Email',
                    'required' => true,
                    'autofocus' => true
                ]
            ],
            'submit_text' => __('Email Password Reset Link')
        ])
    </div>
</div>
@endsection