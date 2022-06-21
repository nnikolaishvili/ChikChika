@extends('layouts.app')

@section('content')
    <div class="h-screen bg-neutral-900 flex justify-center items-center w-full">
        <form action="{{ route('login') }}" method="post">
            @csrf

            <div class="bg-white px-10 py-8 rounded-xl w-screen shadow-md max-w-sm">
                <div class="space-y-4">
                    <h1 class="text-center text-2xl text-gray-600 font-lobster">Login <i
                            class="fa-solid fa-dove"></i></h1>
                    <div>
                        <label for="email" class="block mb-1 text-gray-600">Email</label>
                        <input type="text" id="email" name="email" value="{{ old('email') }}"
                               class="bg-indigo-50 px-4 py-2 outline-none rounded-md w-full" required autofocus/>

                        @error('email')
                        <div class="alert-danger">{{ $message }}</div>
                        @enderror
                    </div>
                    <div>
                        <label for="password" class="block mb-1 text-gray-600">Password</label>
                        <input type="password" id="password" name="password"
                               class="bg-indigo-50 px-4 py-2 outline-none rounded-md w-full" required/>
                    </div>
                    <div class="flex items-center">
                        <input type="checkbox" id="remember" name="remember" class="mr-2"/>
                        <label for="remember" class="text-gray-500 text-sm">Remember me</label>
                    </div>
                </div>
                <button
                    class="mt-4 w-full bg-gradient-to-tr from-blue-600 to-indigo-600 text-indigo-100 py-2 rounded-md text-lg tracking-wide">
                    Login
                </button>

                <div class="mt-4 text-center">
                    <a href="{{ route('register') }}" class="text-sm text-gray-600 hover:text-gray-900">
                        Not signed up yet? Create account
                    </a>
                </div>
            </div>
        </form>
    </div>
@endsection
