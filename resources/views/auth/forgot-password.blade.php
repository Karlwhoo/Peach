<!-- resources/views/auth/forgot-password.blade.php -->

<x-guest-layout>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-br from-peach-100 to-peach-200">
        <div class="w-full max-w-md p-8 space-y-6 bg-white shadow-xl rounded-xl transform transition-all hover:scale-[1.01]">
            <!-- Logo and Title -->
            <div class="text-center space-y-4">
                <a href="/" class="inline-block">
                    <img src="{{ asset('images/logo.png') }}" alt="Application Logo" 
                         class="w-24 h-24 mx-auto transition-transform hover:rotate-3"/>
                </a>
                <h2 class="text-3xl font-bold text-gray-800">
                    {{ __('Password Recovery') }}
                </h2>
            </div>

            <!-- Instructional Text -->
            <div class="text-center">
                <p class="text-gray-600 text-sm leading-relaxed">
                    {{ __('Forgot your password? No problem. Just enter your email address below, and we will send you a link to reset your password.') }}
                </p>
            </div>

            <!-- Session Status -->
            @if (session('status'))
                <div class="bg-green-50 text-green-700 p-4 rounded-lg text-sm text-center animate-fade-in">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('status') }}
                </div>
            @endif

            <!-- Validation Errors -->
            @if ($errors->any())
                <div class="bg-red-50 text-red-700 p-4 rounded-lg text-sm">
                    <ul class="list-none space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>
                                <i class="fas fa-exclamation-circle mr-2"></i>
                                {{ $error }}
                            </li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Password Reset Form -->
            <form method="POST" action="{{ route('password.email') }}" class="space-y-6">
                @csrf

                <!-- Email Address -->
                <div class="space-y-2">
                    <x-label for="email" :value="__('Email Address')" 
                            class="text-gray-700 font-medium"/>
                    <div class="relative">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <i class="fas fa-envelope text-gray-400"></i>
                        </div>
                        <x-input id="email" 
                                class="block w-full pl-10 pr-3 py-2.5 border border-gray-300 rounded-lg focus:ring-2 focus:ring-peach-200 focus:border-peach-400 transition-colors"
                                type="email" 
                                name="email" 
                                :value="old('email')" 
                                required 
                                autofocus 
                                placeholder="you@example.com"/>
                    </div>
                </div>

                <!-- Submit Button -->
                <div>
                    <button type="submit" 
                            class="w-full px-4 py-3 bg-gradient-to-r from-peach-400 to-peach-500 text-white font-semibold rounded-lg shadow-md hover:from-peach-500 hover:to-peach-600 focus:outline-none focus:ring-2 focus:ring-peach-400 focus:ring-offset-2 transform transition-all hover:-translate-y-0.5">
                        <i class="fas fa-paper-plane mr-2"></i>
                        {{ __('Send Reset Link') }}
                    </button>
                </div>
            </form>

            <!-- Back to Login Link -->
            <div class="text-center">
                <a href="{{ route('login') }}" 
                   class="inline-flex items-center text-sm text-gray-600 hover:text-gray-900 transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>
                    {{ __('Back to Login') }}
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>
