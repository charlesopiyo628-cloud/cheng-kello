@extends('layouts.app')

@section('title', 'System Settings')

@section('content')
<div class="max-w-4xl mx-auto bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">System Settings</h2>

    <form method="POST" action="{{ route('settings.update') }}" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <!-- Business Name -->
        <div class="mb-6">
            <label for="business_name" class="block text-gray-700 text-sm font-bold mb-2">Business Name</label>
            <input type="text" id="business_name" name="business_name"
                   value="{{ old('business_name', App\Http\Controllers\SettingsController::getBusinessName()) }}" required
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <p class="text-gray-600 text-sm mt-1">This name will appear on the login page in capital letters.</p>
        </div>

        <!-- Logo Upload -->
        <div class="mb-6">
            <label for="logo" class="block text-gray-700 text-sm font-bold mb-2">Business Logo</label>

            <!-- Current Logo Preview -->
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Current Logo:</p>
                <div class="flex items-center space-x-4">
                    @if(file_exists(public_path('uploads/logo.png')) || file_exists(public_path('uploads/logo.jpg')) || file_exists(public_path('uploads/logo.jpeg')) || file_exists(public_path('uploads/logo.gif')))
                        @php
                            $logoExt = '';
                            if (file_exists(public_path('uploads/logo.png'))) $logoExt = 'png';
                            elseif (file_exists(public_path('uploads/logo.jpg'))) $logoExt = 'jpg';
                            elseif (file_exists(public_path('uploads/logo.jpeg'))) $logoExt = 'jpeg';
                            elseif (file_exists(public_path('uploads/logo.gif'))) $logoExt = 'gif';
                        @endphp
                        <img src="{{ asset('uploads/logo.' . $logoExt) }}" alt="Current Logo" class="h-16 w-auto border rounded">
                    @else
                        <div class="h-16 w-16 bg-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-white text-2xl font-bold">{{ substr(App\Http\Controllers\SettingsController::getBusinessName(), 0, 1) }}</span>
                        </div>
                    @endif
                    <span class="text-gray-600">Logo displayed on login page</span>
                </div>
            </div>

            <!-- File Upload -->
            <input type="file" id="logo" name="logo" accept="image/*"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <p class="text-gray-600 text-sm mt-1">Upload a new logo (PNG, JPG, JPEG, GIF - Max 2MB). Recommended size: 200x200px.</p>
        </div>

        <!-- Login Page Image Upload -->
        <div class="mb-6">
            <label for="login_image" class="block text-gray-700 text-sm font-bold mb-2">Login Page Background Image</label>

            <!-- Current Login Image Preview -->
            <div class="mb-4">
                <p class="text-sm text-gray-600 mb-2">Current Login Background:</p>
                <div class="flex items-center space-x-4">
                    @if(file_exists(public_path('uploads/login-bg.png')) || file_exists(public_path('uploads/login-bg.jpg')) || file_exists(public_path('uploads/login-bg.jpeg')) || file_exists(public_path('uploads/login-bg.gif')))
                        @php
                            $loginBgExt = '';
                            if (file_exists(public_path('uploads/login-bg.png'))) $loginBgExt = 'png';
                            elseif (file_exists(public_path('uploads/login-bg.jpg'))) $loginBgExt = 'jpg';
                            elseif (file_exists(public_path('uploads/login-bg.jpeg'))) $loginBgExt = 'jpeg';
                            elseif (file_exists(public_path('uploads/login-bg.gif'))) $loginBgExt = 'gif';
                        @endphp
                        <img src="{{ asset('uploads/login-bg.' . $loginBgExt) }}" alt="Current Login Background" class="h-24 w-auto border rounded">
                    @else
                        <div class="h-24 w-32 bg-gray-200 rounded flex items-center justify-center">
                            <span class="text-gray-500 text-sm">No background image</span>
                        </div>
                    @endif
                    <span class="text-gray-600">Background image displayed on login page</span>
                </div>
            </div>

            <!-- File Upload -->
            <input type="file" id="login_image" name="login_image" accept="image/*"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
            <p class="text-gray-600 text-sm mt-1">Upload a background image for the login page (PNG, JPG, JPEG, GIF - Max 5MB). Recommended size: 1920x1080px.</p>
        </div>

        <!-- Submit Button -->
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Update Settings
            </button>
            <a href="{{ route('dashboard') }}" class="inline-block align-baseline font-bold text-sm text-blue-600 hover:text-blue-800">
                Back to Dashboard
            </a>
        </div>
    </form>
</div>
@endsection