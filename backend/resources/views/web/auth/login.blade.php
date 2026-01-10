@extends('layouts.app')

@section('title', __('auth.login_title'))

@section('content')
    <div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8"
        style="background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #f0fdfa 100%);">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-10">
                <!-- Logo -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center gap-2 mb-4">
                        <div
                            class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-xl flex items-center justify-center">
                            <span class="text-white font-bold text-2xl">S</span>
                        </div>
                        <span class="text-2xl font-bold text-gray-900">Saudi<span
                                class="text-emerald-600">Prop</span></span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ __('auth.login_title') }}</h2>
                    <p class="text-gray-600 mt-2">Sign in to your account</p>
                </div>

                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-100 text-emerald-700 rounded-xl text-center">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl text-center">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <!-- Login Form -->
                <form action="{{ route('login.submit') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="email"
                            class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.email') }}</label>
                        <input type="email" id="email" name="email" required autofocus autocomplete="email"
                            inputmode="email"
                            class="w-full px-4 py-4 bg-gray-100 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none"
                            placeholder="you@example.com" value="{{ old('email') }}">
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" id="password" name="password" required autocomplete="current-password"
                            class="w-full px-4 py-4 bg-gray-100 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none"
                            placeholder="Enter your password">
                    </div>

                    <div class="flex items-center justify-between">
                        <label class="flex items-center gap-2 cursor-pointer">
                            <input type="checkbox" name="remember"
                                class="w-4 h-4 text-emerald-600 rounded focus:ring-emerald-500">
                            <span class="text-sm text-gray-600">Remember me</span>
                        </label>
                        {{-- <a href="#" class="text-sm text-emerald-600 hover:text-emerald-700">Forgot password?</a> --}}
                    </div>

                    <button type="submit"
                        class="w-full px-6 py-4 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all">
                        Sign In
                    </button>
                </form>

                <div class="mt-8 text-center text-sm text-gray-600">
                    {{ __('auth.no_account') }}
                    <a href="{{ route('register') }}"
                        class="text-emerald-600 hover:text-emerald-700 font-medium">{{ __('auth.register') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection