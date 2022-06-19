@extends('layouts.app')

<div class="h-screen bg-gradient-to-br from-blue-600 to-indigo-600 flex justify-center items-center w-full">
    <div class="bg-white px-10 py-8 rounded-xl w-screen shadow-md max-w-sm">
        <div class="space-y-4">
            <form method="POST" action="{{ route('logout') }}">
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
