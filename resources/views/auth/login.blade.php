@extends('layouts.app')

@push('styles')
    <style>
        .login-page {
            display: flex;
            justify-content: center;
            align-items: center;
            /* min-height: 100vh; */
            page-break-after: auto;
            padding: 1rem;
            border-radius: 15px;
            /* background-color: #f3f4f685; */
            /* Background color equivalent to bg-gray-100 */
        }

        .login-container {
            max-width: 24rem;
            /* Equivalent to max-w-md */
            padding: 1.5rem;
            /* Equivalent to p-6 */
            /* background-color: #ffffff41; */
            /* Equivalent to bg-white */
            border-radius: 0.5rem;
            /* Equivalent to rounded-lg */
            box-shadow: 0 10px 15px rgba(0, 0, 0, 0.1);
            /* Equivalent to shadow */
        }
    </style>
@endpush

@section('content')
<div class="container">
    <div class="login-page">
        <div class="login-container">
            <!-- Logo Container -->
            <div class="flex justify-center mb-6">
                <a href="#" class="text-2xl font-semibold text-gray-900 dark:text-white">
                    <img class="w-48 h-auto"
                        src="https://externalliance.fr/wp-content/uploads/2023/01/logo-externalliance.png"
                        alt="ExternAlliance Logo">
                </a>
            </div>
            <hr class="w-48 h-1 mx-auto my-4 bg-gray-100 border-0 rounded md:my-10 dark:bg-gray-700">

            <div class="space-y-6">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white text-center">
                    Connectez-vous à votre compte
                </h2>
                <hr class="w-48 h-1 mx-auto my-4 bg-gray-100 border-0 rounded md:my-10 dark:bg-gray-700">

                <!-- Status Message -->
                @include('components.auth-session-status', ['status' => session('status')])

                <!-- Login Form -->
                @include('components.validation-form', [
                    'action' => route('login'),
                    'fields' => [
                        [
                            'type' => 'email',
                            'name' => 'email',
                            'label' => __('Email'),
                            'placeholder' => 'nom@entreprise.com',
                            'required' => true,
                            'autofocus' => true,
                            'autocomplete' => 'username',
                        ],
                        [
                            'type' => 'password',
                            'name' => 'password',
                            'label' => __('Password'),
                            'placeholder' => '••••••••',
                            'required' => true,
                            'autocomplete' => 'current-password',
                        ],
                        [
                            'type' => 'checkbox',
                            'name' => 'remember',
                            'label' => __('Se souvenir de moi'),
                            'class' =>
                                'w-4 h-4 text-primary-600 bg-gray-100 border-gray-300 rounded focus:ring-primary-500 dark:bg-gray-700 dark:border-gray-600 dark:focus:ring-primary-600 dark:ring-offset-gray-800 focus:ring-2',
                        ],
                    ],
                    'additional_content' =>
                        '
                                    <div class="flex items-center justify-between mb-4">
                                        <a href="' .
                        route('password.request') .
                        '" class="text-sm font-medium text-primary-600 hover:underline dark:text-primary-500">
                                            ' .
                        __('Mot de passe oublié ?') .
                        '
                                        </a>
                                    </div>
                                ',
                    'submit_text' => __('Connexion'),
                    'submit_class' =>
                        ' from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2',
                    'after_submit' =>
                        '
                                    <p class="text-sm font-light text-gray-500 dark:text-gray-400">
                                        Vous n\'avez pas encore de compte ?
                                        <a href="' .
                        route('register') .
                        '" from-purple-500 via-purple-600 to-purple-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-purple-300 dark:focus:ring-purple-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                            Inscrivez-vous
                                        </a>
                                    </p>
                                ',
                ])
            </div>
        </div>
    </div>
</div>
@endsection
