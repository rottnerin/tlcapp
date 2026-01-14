@extends('layouts.user')

@section('title', 'Profile Settings - TLC Professional Learning')

@push('styles')
<style>
    :root {
        --tlc-navy: #0d3b66;
        --tlc-cream: #faf0ca;
        --tlc-gold: #f4d35e;
        --tlc-orange: #ee964b;
    }
</style>
@endpush

@section('content')
<div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
    <!-- Header -->
    <div class="mb-8">
        <h1 class="text-3xl font-bold mb-2" style="color: var(--tlc-navy);">Profile Settings</h1>
        <p style="color: #4a5568;">Manage your account information and preferences</p>
    </div>

    <!-- Success/Error Messages -->
    @if (session('success'))
        <div class="mb-6 p-4 rounded-lg" style="background-color: rgba(244, 211, 94, 0.2); border: 1px solid var(--tlc-gold); color: var(--tlc-navy);">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="mb-6 p-4 rounded-lg" style="background-color: #fee2e2; border: 1px solid #f87171; color: #b91c1c;">
            <ul class="list-disc list-inside">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Profile Information Form -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-lg shadow-sm p-6" style="border-left: 4px solid var(--tlc-gold);">
                <h2 class="text-xl font-semibold mb-6" style="color: var(--tlc-navy);">Personal Information</h2>

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Name -->
                    <div>
                        <label for="name" class="block text-sm font-medium mb-1" style="color: var(--tlc-navy);">Full Name</label>
                        <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}"
                               class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2"
                               style="border-color: rgba(13, 59, 102, 0.2); --tw-ring-color: var(--tlc-gold);"
                               required>
                        @error('name')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Email -->
                    <div>
                        <label for="email" class="block text-sm font-medium mb-1" style="color: var(--tlc-navy);">Email Address</label>
                        <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}"
                               class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2"
                               style="border-color: rgba(13, 59, 102, 0.2); --tw-ring-color: var(--tlc-gold);"
                               required>
                        <p class="mt-1 text-sm" style="color: #718096;">Your AES Google account email address</p>
                        @error('email')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Phone -->
                    <div>
                        <label for="phone" class="block text-sm font-medium mb-1" style="color: var(--tlc-navy);">Phone Number</label>
                        <input type="tel" id="phone" name="phone" value="{{ old('phone', $user->phone) }}"
                               class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2"
                               style="border-color: rgba(13, 59, 102, 0.2); --tw-ring-color: var(--tlc-gold);"
                               placeholder="(555) 123-4567">
                        <p class="mt-1 text-sm" style="color: #718096;">Optional - for emergency contact purposes</p>
                        @error('phone')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Division (Read-only) -->
                    <div>
                        <label class="block text-sm font-medium mb-1" style="color: var(--tlc-navy);">Division</label>
                        <div class="px-3 py-2 rounded-md" style="background-color: rgba(250, 240, 202, 0.5); border: 1px solid rgba(13, 59, 102, 0.2); color: var(--tlc-navy);">
                            {{ $user->division ? $user->division->full_name : 'Not assigned' }}
                        </div>
                        <p class="mt-1 text-sm" style="color: #718096;">Your division is set based on your Google account domain</p>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 text-white font-medium rounded-lg transition-colors"
                                style="background-color: var(--tlc-orange);"
                                onmouseover="this.style.backgroundColor='#0d3b66'"
                                onmouseout="this.style.backgroundColor='#ee964b'">
                            Update Profile
                        </button>
                    </div>
                </form>
            </div>

            <!-- Change Password -->
            <div class="bg-white rounded-lg shadow-sm p-6 mt-6" style="border-left: 4px solid var(--tlc-navy);">
                <h2 class="text-xl font-semibold mb-6" style="color: var(--tlc-navy);">Change Password</h2>

                <form method="POST" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('PATCH')

                    <!-- Current Password -->
                    <div>
                        <label for="current_password" class="block text-sm font-medium mb-1" style="color: var(--tlc-navy);">Current Password</label>
                        <input type="password" id="current_password" name="current_password"
                               class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2"
                               style="border-color: rgba(13, 59, 102, 0.2); --tw-ring-color: var(--tlc-gold);">
                        @error('current_password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- New Password -->
                    <div>
                        <label for="password" class="block text-sm font-medium mb-1" style="color: var(--tlc-navy);">New Password</label>
                        <input type="password" id="password" name="password"
                               class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2"
                               style="border-color: rgba(13, 59, 102, 0.2); --tw-ring-color: var(--tlc-gold);">
                        <p class="mt-1 text-sm" style="color: #718096;">Minimum 8 characters</p>
                        @error('password')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Confirm Password -->
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium mb-1" style="color: var(--tlc-navy);">Confirm New Password</label>
                        <input type="password" id="password_confirmation" name="password_confirmation"
                               class="w-full px-3 py-2 border rounded-md shadow-sm focus:outline-none focus:ring-2"
                               style="border-color: rgba(13, 59, 102, 0.2); --tw-ring-color: var(--tlc-gold);">
                        @error('password_confirmation')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end">
                        <button type="submit" class="px-6 py-2 text-white font-medium rounded-lg transition-colors"
                                style="background-color: var(--tlc-navy);"
                                onmouseover="this.style.backgroundColor='#164773'"
                                onmouseout="this.style.backgroundColor='#0d3b66'">
                            Update Password
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Sidebar -->
        <div class="space-y-6">
            <!-- Account Information -->
            <div class="bg-white rounded-lg shadow-sm p-6" style="border-top: 3px solid var(--tlc-gold);">
                <h3 class="text-lg font-semibold mb-4" style="color: var(--tlc-navy);">Account Information</h3>
                <div class="space-y-3">
                    <div>
                        <span class="text-sm font-medium" style="color: #718096;">Account Created:</span>
                        <p class="text-sm" style="color: var(--tlc-navy);">{{ $user->created_at->format('M j, Y') }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium" style="color: #718096;">Last Updated:</span>
                        <p class="text-sm" style="color: var(--tlc-navy);">{{ $user->updated_at->format('M j, Y') }}</p>
                    </div>
                    <div>
                        <span class="text-sm font-medium" style="color: #718096;">Account Status:</span>
                        <p class="text-sm" style="color: var(--tlc-navy);">Active</p>
                    </div>
                </div>
            </div>

            <!-- Danger Zone -->
            <div class="bg-white rounded-lg shadow-sm p-6 border border-red-200">
                <h3 class="text-lg font-semibold text-red-900 mb-4">Danger Zone</h3>
                <p class="text-sm text-gray-600 mb-4">
                    Once you delete your account, there is no going back. Please be certain.
                </p>

                <button type="button"
                        onclick="document.getElementById('delete-account-modal').classList.remove('hidden')"
                        class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition-colors">
                    Delete Account
                </button>
            </div>
        </div>
    </div>

    <!-- Delete Account Modal -->
    <div id="delete-account-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white">
            <div class="mt-3">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Delete Account</h3>
                <p class="text-sm text-gray-600 mb-4">
                    This action cannot be undone. This will permanently delete your account and remove your data from our servers.
                </p>

                <form method="POST" action="{{ route('profile.destroy') }}">
                    @csrf
                    @method('DELETE')

                    <div class="mb-4">
                        <label for="delete_password" class="block text-sm font-medium text-gray-700 mb-1">
                            Enter your password to confirm
                        </label>
                        <input type="password" id="delete_password" name="password"
                               class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-2 focus:ring-red-500 focus:border-red-500"
                               required>
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button"
                                onclick="document.getElementById('delete-account-modal').classList.add('hidden')"
                                class="px-4 py-2 bg-gray-300 text-gray-700 text-sm font-medium rounded-lg hover:bg-gray-400 transition-colors">
                            Cancel
                        </button>
                        <button type="submit"
                                class="px-4 py-2 bg-red-600 text-white text-sm font-medium rounded-lg hover:bg-red-700 transition-colors">
                            Delete Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection