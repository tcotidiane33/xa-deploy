@extends('layouts.main')

@section('content')
<div class="container mx-auto p-4 pt-6 md:p-6">
    <h2 class="text-2xl font-bold mb-4">{{ __('Verify Email') }}</h2>
    <div class="bg-white rounded-lg shadow-md p-4">
        <div class="mb-4">
            {{ __('Thanks for signing up! Before getting started, could you verify your email address by clicking on the link we just emailed to you? If you didn\'t receive the email, we will gladly send you another.') }}
        </div>

        @if (session('status') == 'verification-link-sent')
            <div class="mb-4 text-sm text-green-600 dark:text-green-400">
                {{ __('A new verification link has been sent to the email address you provided during registration.') }}
            </div>
        @endif

        <div class="mt-4 flex items-center justify-between">
            <form method="POST" action="{{ route('verification.send') }}">
                @csrf
                <button type="submit" class="btn bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    {{ __('Resend Verification Email') }}
                </button>
            </form>

            <form method="POST" action="{{ route('logout') }}">
                @csrf
                <button type="submit" class="text-blue-600 hover:text-blue-700">
                    {{ __('Log Out') }}
                </button>
            </form>
        </div>
    </div>
</div>
@endsection