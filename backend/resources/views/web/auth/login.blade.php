@extends('layouts.app')

@section('title', __('auth.login_title'))

@section('content')
    <div class="min-h-[80vh] flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8" style="background: linear-gradient(135deg, #f0fdf4 0%, #ecfdf5 50%, #f0fdfa 100%);">
        <div class="max-w-md w-full">
            <div class="bg-white rounded-3xl shadow-2xl p-8 md:p-10">
                <!-- Logo -->
                <div class="text-center mb-8">
                    <div class="inline-flex items-center gap-2 mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-emerald-700 rounded-xl flex items-center justify-center">
                            <span class="text-white font-bold text-2xl">S</span>
                        </div>
                        <span class="text-2xl font-bold text-gray-900">Saudi<span class="text-emerald-600">Prop</span></span>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-900">{{ __('auth.login_title') }}</h2>
                    <p class="text-gray-600 mt-2">{{ __('auth.login_subtitle') }}</p>
                </div>

                @if(session('message'))
                    <div class="mb-6 p-4 bg-emerald-100 text-emerald-700 rounded-xl text-center">
                        {{ session('message') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                @if(session('otp_sent'))
                    <!-- OTP Verification Form -->
                    <form action="{{ route('login.verify') }}" method="POST" class="space-y-6">
                        @csrf
                        <input type="hidden" name="email" value="{{ session('email') }}">
                        
                        <div>
                            <label for="code" class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.otp') }}</label>
                            <input type="text" id="code" name="code" maxlength="6" pattern="[0-9]{6}" required autofocus
                                class="w-full px-4 py-4 bg-gray-100 rounded-xl text-center text-2xl tracking-widest font-mono focus:ring-2 focus:ring-emerald-500 outline-none"
                                placeholder="000000">
                        </div>

                        <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all">
                            {{ __('auth.verify') }}
                        </button>
                    </form>

                    <div class="mt-6 text-center">
                        <form action="{{ route('login.send-otp') }}" method="POST" class="inline">
                            @csrf
                            <input type="hidden" name="email" value="{{ session('email') }}">
                            <button type="submit" class="text-emerald-600 hover:text-emerald-700 text-sm">
                                Re-send verification code
                            </button>
                        </form>
                    </div>
                @else
                    <!-- Email Form -->
                    <form action="{{ route('login.send-otp') }}" method="POST" class="space-y-6">
                        @csrf
                        
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-700 mb-2">{{ __('auth.email') }}</label>
                            <input type="email" id="email" name="email" required autofocus
                                class="w-full px-4 py-4 bg-gray-100 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none"
                                placeholder="you@example.com"
                                value="{{ old('email') }}">
                        </div>

                        <button type="submit" class="w-full px-6 py-4 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all">
                            {{ __('auth.send_otp') }}
                        </button>
                    </form>
                @endif

                <div class="mt-8 text-center text-sm text-gray-600">
                    {{ __('auth.no_account') }}
                    <a href="#" class="text-emerald-600 hover:text-emerald-700 font-medium">{{ __('auth.register') }}</a>
                </div>
            </div>
        </div>
    </div>
@endsection
