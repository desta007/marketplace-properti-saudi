@extends('layouts.app')

@section('title', __('nav.about'))

@section('content')
    <!-- Hero Section -->
    <section class="bg-gradient-to-br from-emerald-900 via-emerald-800 to-teal-900 text-white py-20">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">{{ __('nav.about') }}</h1>
            <p class="text-xl text-emerald-100">Saudi Arabia's Premier Real Estate Marketplace</p>
        </div>
    </section>

    <!-- About Content -->
    <section class="py-16 bg-white">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="prose prose-lg max-w-none">
                <h2 class="text-3xl font-bold text-gray-900 mb-6">Who We Are</h2>
                <p class="text-gray-600 mb-6">
                    SaudiProp is the leading real estate marketplace in Saudi Arabia, connecting property seekers with
                    verified agents and premium listings across the Kingdom. Our platform is designed to make your property
                    search journey seamless, transparent, and efficient.
                </p>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 mt-12">Our Mission</h2>
                <p class="text-gray-600 mb-6">
                    We aim to revolutionize the real estate experience in Saudi Arabia by leveraging technology to provide
                    comprehensive property listings, verified agent networks, and innovative search tools that empower
                    buyers, sellers, and renters alike.
                </p>

                <h2 class="text-3xl font-bold text-gray-900 mb-6 mt-12">Why Choose SaudiProp?</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-8">
                    <div class="bg-gray-50 p-6 rounded-2xl">
                        <div class="w-12 h-12 bg-emerald-100 rounded-xl flex items-center justify-center mb-4">
                            <span class="text-2xl">✓</span>
                        </div>
                        <h3 class="font-bold text-lg mb-2">Verified Listings</h3>
                        <p class="text-gray-600">All properties are verified by our team to ensure accuracy and
                            authenticity.</p>
                    </div>
                    <div class="bg-gray-50 p-6 rounded-2xl">
                        <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center mb-4">
                            <span class="text-2xl">🏢</span>
                        </div>
                        <h3 class="font-bold text-lg mb-2">REGA Licensed Agents</h3>
                        <p class="text-gray-600">Work with certified agents who meet Saudi Arabia's real estate regulations.
                        </p>
                    </div>
                    <div class="bg-gray-50 p-6 rounded-2xl">
                        <div class="w-12 h-12 bg-purple-100 rounded-xl flex items-center justify-center mb-4">
                            <span class="text-2xl">🗺️</span>
                        </div>
                        <h3 class="font-bold text-lg mb-2">Interactive Maps</h3>
                        <p class="text-gray-600">Explore properties with our advanced map-based search feature.</p>
                    </div>
                    <div class="bg-gray-50 p-6 rounded-2xl">
                        <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center mb-4">
                            <span class="text-2xl">📱</span>
                        </div>
                        <h3 class="font-bold text-lg mb-2">Direct Communication</h3>
                        <p class="text-gray-600">Contact agents directly through our platform for quick responses.</p>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Contact CTA -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <h2 class="text-3xl font-bold text-gray-900 mb-4">Get In Touch</h2>
            <p class="text-gray-600 mb-8">Have questions? We'd love to hear from you.</p>
            <div class="flex flex-wrap justify-center gap-6">
                <div class="flex items-center gap-2 text-gray-700">
                    <span>📧</span>
                    <span>{{ \App\Models\Setting::get('site_email', 'info@saudiprop.com') }}</span>
                </div>
                <div class="flex items-center gap-2 text-gray-700">
                    <span>📱</span>
                    <span>{{ \App\Models\Setting::get('site_phone', '+966 50 123 4567') }}</span>
                </div>
                <div class="flex items-center gap-2 text-gray-700">
                    <span>📍</span>
                    <span>{{ \App\Models\Setting::get('site_address', 'Riyadh, Saudi Arabia') }}</span>
                </div>
            </div>
        </div>
    </section>
@endsection