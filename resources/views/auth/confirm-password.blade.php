@extends('layouts.main')

@section('content')
<div class="container mx-auto p-4 pt-6 md:p-6">
    <h2 class="text-2xl font-bold mb-4">{{ __('Confirm Password') }}</h2>
    <div class="bg-white rounded-lg shadow-md p-4">
        <div class="mb-4">
            {{ __('This is a secure area of the application. Please confirm your password before continuing.') }}
        </div>

        @include('components.validation-form', [
            'action' => route('password.confirm'),
            'fields' => [
                [
                    'type' => 'password',
                    'name' => 'password',
                    'label' => __('Password'),
                    'placeholder' => 'Enter Your Password',
                    'required' => true,
                    'autocomplete' => 'current-password'
                ]
            ],
            'submit_text' => __('Confirm')
        ])
    </div>
</div>
@endsection