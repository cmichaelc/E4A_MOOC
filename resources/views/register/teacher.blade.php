<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Teacher Registration - E4A_MOOC Benin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-900 text-gray-100">
    <div class="min-h-screen flex flex-col">
        <!-- Header -->
        <nav class="bg-gray-800 border-b border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between h-16">
                    <div class="flex items-center">
                        <a href="{{ url('/') }}" class="text-xl font-bold text-blue-400">
                            E4A_MOOC Benin
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-gray-200">
                            Already registered? Login
                        </a>
                        <a href="{{ route('register.school') }}" class="text-sm text-blue-400 hover:text-blue-300">
                            Register a School
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 py-12">
            <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-white mb-4">Teacher Registration</h1>
                    <p class="text-lg text-gray-400">Join E4A_MOOC Benin as a teacher</p>
                </div>

                <!-- Registration Form -->
                <div class="bg-gray-800 rounded-lg shadow-xl p-8">
                    @if(session('error'))
                        <div class="mb-6 px-4 py-3 rounded-lg bg-red-500/10 border border-red-500 text-red-400">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.teacher.store') }}" class="space-y-6">
                        @csrf

                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300 mb-2">
                                Full Name <span class="text-red-400">*</span>
                            </label>
                            <input type="text" name="name" id="name" required value="{{ old('name') }}"
                                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="Your full name">
                            @error('name')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300 mb-2">
                                Email Address <span class="text-red-400">*</span>
                            </label>
                            <input type="email" name="email" id="email" required value="{{ old('email') }}"
                                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="your.email@example.com">
                            @error('email')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-300 mb-2">
                                Phone Number <span class="text-red-400">*</span>
                            </label>
                            <input type="tel" name="phone" id="phone" required value="{{ old('phone') }}"
                                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                placeholder="+229 XX XX XX XX">
                            @error('phone')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label for="specialization" class="block text-sm font-medium text-gray-300 mb-2">
                                Specialization / Subject Area <span class="text-red-400">*</span>
                            </label>
                            <select name="specialization" id="specialization" required
                                class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                <option value="">-- Select Your Specialization --</option>
                                <option value="Mathematics" {{ old('specialization') == 'Mathematics' ? 'selected' : '' }}>Mathematics</option>
                                <option value="Languages" {{ old('specialization') == 'Languages' ? 'selected' : '' }}>
                                    Languages (French, English)</option>
                                <option value="Sciences" {{ old('specialization') == 'Sciences' ? 'selected' : '' }}>
                                    Sciences (Physics, Chemistry, Biology)</option>
                                <option value="History" {{ old('specialization') == 'History' ? 'selected' : '' }}>History
                                    & Geography</option>
                                <option value="Arts" {{ old('specialization') == 'Arts' ? 'selected' : '' }}>Arts & Music
                                </option>
                                <option value="Physical Education" {{ old('specialization') == 'Physical Education' ? 'selected' : '' }}>Physical Education</option>
                                <option value="General" {{ old('specialization') == 'General' ? 'selected' : '' }}>General
                                    Education</option>
                                <option value="Other" {{ old('specialization') == 'Other' ? 'selected' : '' }}>Other
                                </option>
                            </select>
                            @error('specialization')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="bg-blue-500/10 border border-blue-500/50 rounded-lg p-4">
                            <p class="text-sm text-blue-300">
                                <strong>After Registration:</strong>
                            </p>
                            <ul class="mt-2 ml-5 text-sm text-blue-200 list-disc space-y-1">
                                <li>You'll receive your login credentials</li>
                                <li>School managers can find you and assign you to classes</li>
                                <li>You'll be able to manage grades and attendance</li>
                            </ul>
                        </div>

                        <!-- Terms and Conditions -->
                        <div>
                            <label class="flex items-start space-x-3">
                                <input type="checkbox" name="terms" value="1" required
                                    class="mt-1 w-4 h-4 text-blue-600 bg-gray-700 border-gray-600 rounded focus:ring-blue-500">
                                <span class="text-sm text-gray-300">
                                    I agree to the <a href="#" class="text-blue-400 hover:text-blue-300 underline">Terms
                                        and Conditions</a>
                                    and <a href="#" class="text-blue-400 hover:text-blue-300 underline">Privacy
                                        Policy</a>
                                </span>
                            </label>
                            @error('terms')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Submit Button -->
                        <div class="flex gap-4">
                            <button type="submit"
                                class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-semibold text-lg transition shadow-lg hover:shadow-xl">
                                Register as Teacher
                            </button>
                            <a href="{{ url('/') }}"
                                class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition text-center">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 border-t border-gray-700 py-6 mt-12">
            <div class="max-w-7xl mx-auto px-4 text-center text-gray-400 text-sm">
                <p>&copy; {{ date('Y') }} E4A_MOOC Benin. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>

</html>