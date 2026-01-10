@extends('layouts.app')

@section('title', __('auth.register'))

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
                    <h2 class="text-2xl font-bold text-gray-900">Create Account</h2>
                    <p class="text-gray-600 mt-2">Join SaudiProp to find your dream property</p>
                </div>

                @if(session('success'))
                    <div class="mb-6 p-4 bg-emerald-100 text-emerald-700 rounded-xl text-center">
                        {{ session('success') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="mb-6 p-4 bg-red-100 text-red-700 rounded-xl">
                        @foreach($errors->all() as $error)
                            <p>{{ $error }}</p>
                        @endforeach
                    </div>
                @endif

                <!-- Registration Form -->
                <form action="{{ route('register.submit') }}" method="POST" class="space-y-6">
                    @csrf

                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700 mb-2">Full Name</label>
                        <input type="text" id="name" name="name" required autofocus autocomplete="name" minlength="2"
                            maxlength="255"
                            class="w-full px-4 py-4 bg-gray-100 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none"
                            placeholder="Your full name" value="{{ old('name') }}">
                    </div>

                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 mb-2">Email Address</label>
                        <input type="email" id="email" name="email" required autocomplete="email" inputmode="email"
                            class="w-full px-4 py-4 bg-gray-100 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none"
                            placeholder="you@example.com" value="{{ old('email') }}">
                    </div>

                    <div>
                        <label for="phone" class="block text-sm font-medium text-gray-700 mb-2">Phone Number</label>
                        <input type="tel" id="phone" name="phone" required autocomplete="tel" inputmode="tel"
                            class="w-full px-4 py-4 bg-gray-100 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none"
                            placeholder="+966 50 123 4567" value="{{ old('phone') }}">
                        <p class="text-xs text-gray-500 mt-1">This will be your WhatsApp contact number</p>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-2">Password</label>
                        <input type="password" id="password" name="password" required autocomplete="new-password"
                            minlength="8"
                            class="w-full px-4 py-4 bg-gray-100 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none"
                            placeholder="Minimum 8 characters">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-2">Confirm
                            Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation" required
                            autocomplete="new-password"
                            class="w-full px-4 py-4 bg-gray-100 rounded-xl focus:ring-2 focus:ring-emerald-500 outline-none"
                            placeholder="Repeat your password">
                    </div>

                    <button type="submit"
                        class="w-full px-6 py-4 bg-gradient-to-r from-emerald-600 to-teal-600 text-white font-semibold rounded-xl hover:from-emerald-700 hover:to-teal-700 transition-all">
                        Create Account
                    </button>

                    <p class="text-xs text-gray-500 text-center">
                        By registering, you agree to our <a href="#" class="text-emerald-600">Terms of Service</a>
                        and <a href="#" class="text-emerald-600">Privacy Policy</a>.
                    </p>
                </form>

                <div class="mt-8 text-center text-sm text-gray-600">
                    Already have an account?
                    <a href="{{ route('login') }}" class="text-emerald-600 hover:text-emerald-700 font-medium">Sign In</a>
                </div>
            </div>
        </div>
    </div>
@endsection