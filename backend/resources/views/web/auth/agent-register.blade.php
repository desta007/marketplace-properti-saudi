@extends('layouts.app')

@section('title', 'Register as Agent')

@section('content')
    <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold mb-2">Become a Property Agent</h1>
            <p class="text-gray-600">Register with your REGA license to start listing properties</p>
        </div>

        @if(session('error'))
            <div class="bg-red-50 text-red-700 p-4 rounded-xl mb-6">
                {{ session('error') }}
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-lg p-8">
            <form action="{{ route('agent.register.submit') }}" method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Name -->
                <div class="mb-4">
                    <label class="block font-medium mb-2">Full Name <span class="text-red-500">*</span></label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name) }}" required
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    @error('name') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Phone -->
                <div class="mb-4">
                    <label class="block font-medium mb-2">Phone Number <span class="text-red-500">*</span></label>
                    <input type="tel" name="phone" value="{{ old('phone', auth()->user()->phone) }}" required
                        placeholder="+966 50 123 4567"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    @error('phone') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- WhatsApp -->
                <div class="mb-4">
                    <label class="block font-medium mb-2">WhatsApp Number</label>
                    <input type="tel" name="whatsapp_number"
                        value="{{ old('whatsapp_number', auth()->user()->whatsapp_number) }}" placeholder="+966 50 123 4567"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <p class="text-sm text-gray-500 mt-1">Clients will use this to contact you directly</p>
                    @error('whatsapp_number') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- REGA License Number -->
                <div class="mb-4">
                    <label class="block font-medium mb-2">REGA License Number <span class="text-red-500">*</span></label>
                    <input type="text" name="rega_license_number" value="{{ old('rega_license_number') }}" required
                        placeholder="e.g. FA12345678"
                        class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-emerald-500 focus:border-emerald-500">
                    <p class="text-sm text-gray-500 mt-1">Your Fal license number from REGA (10-20 characters)</p>
                    @error('rega_license_number') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- REGA License Document -->
                <div class="mb-6">
                    <label class="block font-medium mb-2">REGA License Document <span class="text-red-500">*</span></label>
                    <div
                        class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-emerald-500 transition-colors">
                        <input type="file" name="rega_license_document" id="license_file" required
                            accept=".pdf,.jpg,.jpeg,.png" class="hidden" onchange="updateFileName(this)">
                        <label for="license_file" class="cursor-pointer">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z" />
                            </svg>
                            <p class="text-gray-600" id="file_name">Click to upload your license document</p>
                            <p class="text-sm text-gray-400 mt-1">PDF, JPG, PNG (max 5MB)</p>
                        </label>
                    </div>
                    @error('rega_license_document') <p class="text-red-500 text-sm mt-1">{{ $message }}</p> @enderror
                </div>

                <!-- Terms -->
                <div class="mb-6">
                    <label class="flex items-start gap-3">
                        <input type="checkbox" name="terms" required
                            class="mt-1 w-5 h-5 text-emerald-600 rounded focus:ring-emerald-500">
                        <span class="text-gray-600">
                            I confirm that the information provided is accurate and I have a valid REGA license.
                            I agree to the <a href="#" class="text-emerald-600 hover:underline">Terms of Service</a>
                            and <a href="#" class="text-emerald-600 hover:underline">Privacy Policy</a>.
                        </span>
                    </label>
                </div>

                <button type="submit"
                    class="w-full px-6 py-4 bg-emerald-600 text-white font-semibold rounded-xl hover:bg-emerald-700 transition-colors">
                    Submit for Verification
                </button>

                <p class="text-center text-gray-500 text-sm mt-4">
                    Your application will be reviewed by our team within 1-2 business days.
                </p>
            </form>
        </div>
    </div>

    @push('scripts')
        <script>
            function updateFileName(input) {
                const fileName = input.files[0]?.name || 'Click to upload your license document';
                document.getElementById('file_name').textContent = fileName;
            }
        </script>
    @endpush
@endsection