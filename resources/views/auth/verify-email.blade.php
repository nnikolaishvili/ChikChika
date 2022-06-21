@extends('layouts.app')

@section('content')
    <div class="h-screen bg-neutral-900 flex justify-center items-center w-full">
        <div class="bg-white px-10 py-8 rounded-xl w-screen shadow-md max-w-sm">
            <div class="space-y-4">
                <h6 class="text-center text-2xl font-semibold text-gray-600 font-lobster">
                    Thanks for signing up!
                </h6>

                <div>Before getting started, could you verify your email address by clicking on the link we just emailed
                    to you? If you didn't receive the email, we will gladly send you another.
                </div>

                @if (session('status') == 'verification-link-sent')
                    <div class="text-sm text-green-600">
                        {{ __('A new verification link has been sent to the email address you provided during registration.') }}
                    </div>
                @endif

                <div class="mt-4">
                    <form method="POST" action="{{ route('verification.send') }}">
                        @csrf

                        <div>
                            <button
                                class="w-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-indigo-100 py-2 rounded-md text-md tracking-wide">
                                Resend Verification Email
                            </button>
                        </div>
                    </form>

                    <form method="POST" action="{{ route('logout') }}" class="mt-2">
                        @csrf

                        <div class="text-center">
                            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900">
                                Log out
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
