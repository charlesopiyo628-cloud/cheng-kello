@extends('layouts.app')

@section('title', 'Add User')

@section('content')
<div class="max-w-md mx-auto bg-white rounded-lg shadow-md p-4 sm:p-6 mx-4">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Add New User</h2>
    
    <form method="POST" action="{{ route('users.store') }}">
        @csrf
        
        <div class="mb-4">
            <label for="name" class="block text-gray-700 text-sm font-bold mb-2">Name *</label>
            <input type="text" id="name" name="name" value="{{ old('name') }}" required
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        
        <div class="mb-4">
            <label for="email" class="block text-gray-700 text-sm font-bold mb-2">Email *</label>
            <input type="email" id="email" name="email" value="{{ old('email') }}" required
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        
        <div class="mb-4">
            <label for="password" class="block text-gray-700 text-sm font-bold mb-2">Password *</label>
            <input type="password" id="password" name="password" required
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        
        <div class="mb-4">
            <label for="password_confirmation" class="block text-gray-700 text-sm font-bold mb-2">Confirm Password *</label>
            <input type="password" id="password_confirmation" name="password_confirmation" required
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        
        <div class="mb-4">
            <label for="role" class="block text-gray-700 text-sm font-bold mb-2">Role *</label>
            <select id="role" name="role" required
                    class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                <option value="">Select Role</option>
                <option value="admin" {{ old('role') == 'admin' ? 'selected' : '' }}>Admin</option>
                <option value="director" {{ old('role') == 'director' ? 'selected' : '' }}>Director</option>
                <option value="cashier" {{ old('role') == 'cashier' ? 'selected' : '' }}>Cashier</option>
            </select>
        </div>
        
        <div class="mb-4">
            <label for="phone" class="block text-gray-700 text-sm font-bold mb-2">Phone</label>
            <input type="text" id="phone" name="phone" value="{{ old('phone') }}"
                   class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
        </div>
        
        <div class="mb-6">
            <label for="address" class="block text-gray-700 text-sm font-bold mb-2">Address</label>
            <textarea id="address" name="address" rows="3"
                      class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">{{ old('address') }}</textarea>
        </div>
        
        <div class="mb-4">
            <label class="flex items-center">
                <input type="checkbox" name="send_verification_email" value="1" 
                       class="mr-2 leading-tight">
                <span class="text-sm text-gray-700">Send verification email to user</span>
            </label>
        </div>

        <div class="mb-4">
            <label class="flex items-center">
                <input type="checkbox" name="allow_password_change" value="1" 
                       class="mr-2 leading-tight">
                <span class="text-sm text-gray-700">Allow user to change password on first login</span>
            </label>
        </div>
        
        <div class="flex items-center justify-between">
            <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                Add User
            </button>
            <a href="{{ route('users.index') }}" class="inline-block align-baseline font-bold text-sm text-gray-600 hover:text-gray-800">
                Cancel
            </a>
        </div>
    </form>
</div>
@endsection
