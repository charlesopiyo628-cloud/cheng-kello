@extends('layouts.app')

@section('title', 'Login')

@section('content')
@php
    $loginBgPath = null;
    $extensions = ['png', 'jpg', 'jpeg', 'gif'];
    foreach ($extensions as $ext) {
        if (file_exists(public_path('uploads/login-bg.' . $ext))) {
            $loginBgPath = 'uploads/login-bg.' . $ext;
            break;
        }
    }
@endphp

@if($loginBgPath)
<div class="min-h-screen bg-cover bg-center bg-no-repeat bg-fixed" style="background-image: url('{{ asset($loginBgPath) }}'); background-attachment: fixed; background-size: cover; background-position: center center;">
    <div class="min-h-screen bg-black bg-opacity-50 flex items-center justify-center py-4 px-4 sm:py-12 sm:px-6 lg:px-8">
        <div class="max-w-md w-full bg-white bg-opacity-95 rounded-lg shadow-xl p-4 sm:p-6 backdrop-blur-sm mx-4">
@else
<div class="min-h-screen bg-gray-100 flex items-center justify-center py-4 px-4 sm:py-12 sm:px-6 lg:px-8">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-4 sm:p-6 mx-4">
@endif
    <!-- Business Logo and Name -->
    <div class="text-center mb-8">
        <div class="mb-4">
            @php
                $logoPath = null;
                $extensions = ['png', 'jpg', 'jpeg', 'gif'];
                foreach ($extensions as $ext) {
                    if (file_exists(public_path('uploads/logo.' . $ext))) {
                        $logoPath = 'uploads/logo.' . $ext;
                        break;
                    }
                }
            @endphp
            @if($logoPath)
                <img src="{{ asset($logoPath) }}" alt="Business Logo" class="h-16 w-auto mx-auto">
            @else
                <div class="h-16 w-16 bg-blue-600 rounded-full flex items-center justify-center mx-auto">
                    <span class="text-white text-2xl font-bold">{{ substr(App\Http\Controllers\SettingsController::getBusinessName(), 0, 1) }}</span>
                </div>
            @endif
        </div>
        <h1 class="text-3xl font-bold text-gray-800 uppercase tracking-wider">{{ App\Http\Controllers\SettingsController::getBusinessName() }}</h1>
        <p class="text-gray-600 mt-2">CHENG KELO FRESH FISH</p>
    </div>

    <h2 class="text-2xl font-bold text-center mb-6">Login</h2>

    @if(session('success'))
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
            {{ session('success') }}
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            {{ session('error') }}
        </div>
    @endif

    @if($errors->any())
        <div id="error-message" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
            <ul>
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form method="POST" action="/login">
        @csrf

        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>

        <div class="mb-6">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password</label>
            <div class="relative">
                <input type="password" id="password" name="password" required
                       class="shadow appearance-none border rounded w-full py-2 px-3 pr-10 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <button type="button" id="togglePassword" class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-500 hover:text-gray-700">
                    <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                    </svg>
                </button>
            </div>
        </div>

        <div class="text-center">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline w-full mb-4">
                Login
            </button>
            
            <div class="mt-4 space-y-2">
                @if(!\App\Models\User::where('role', 'admin')->exists())
                    <a href="{{ route('register') }}" class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800">
                        Register New User
                    </a>
                @endif
                
                <a href="{{ route('password.request') }}" class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800">
                    Forgot Password?
                </a>
            </div>
        </div>
    </form>
</div>

@if($loginBgPath)
    </div>
</div>
@else
</div>
@endif

<script>
document.getElementById('togglePassword').addEventListener('click', function() {
    const passwordInput = document.getElementById('password');
    const icon = this.querySelector('svg');

    if (passwordInput.type === 'password') {
        passwordInput.type = 'text';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>';
    } else {
        passwordInput.type = 'password';
        icon.innerHTML = '<path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>';
    }
});

// Auto-hide error message after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    const errorMessage = document.getElementById('error-message');
    if (errorMessage) {
        setTimeout(function() {
            errorMessage.style.transition = 'opacity 0.5s ease-out';
            errorMessage.style.opacity = '0';
            setTimeout(function() {
                errorMessage.style.display = 'none';
            }, 500);
        }, 5000);
    }
});
</script>
@endsection
