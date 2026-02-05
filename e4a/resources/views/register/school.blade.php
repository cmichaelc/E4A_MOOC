<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>Register Your School - EduTech Benin</title>
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
                            EduTech Benin
                        </a>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="text-sm text-gray-400 hover:text-gray-200">
                            Already have an account? Login
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-1 py-12">
            <div class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8">
                <!-- Header Section -->
                <div class="text-center mb-8">
                    <h1 class="text-4xl font-bold text-white mb-4">Register Your School</h1>
                    <p class="text-lg text-gray-400">Join EduTech Benin and start managing your school digitally</p>
                </div>

                <!-- Registration Form -->
                <div class="bg-gray-800 rounded-lg shadow-xl p-8">
                    @if(session('error'))
                        <div class="mb-6 px-4 py-3 rounded-lg bg-red-500/10 border border-red-500 text-red-400">
                            {{ session('error') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('register.school.store') }}" class="space-y-8">
                        @csrf

                        <!-- School Information Section -->
                        <div>
                            <h2 class="text-2xl font-semibold text-white mb-4 pb-2 border-b border-gray-700">
                                School Information
                            </h2>
                            <div class="space-y-4">
                                <div>
                                    <label for="school_name" class="block text-sm font-medium text-gray-300 mb-2">
                                        School Name <span class="text-red-400">*</span>
                                    </label>
                                    <input type="text" name="school_name" id="school_name" required
                                        value="{{ old('school_name') }}"
                                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="e.g., École Primaire Cotonou">
                                    @error('school_name')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="school_type" class="block text-sm font-medium text-gray-300 mb-2">
                                        School Type <span class="text-red-400">*</span>
                                    </label>
                                    <select name="school_type" id="school_type" required
                                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                                        <option value="">-- Select Type --</option>
                                        <option value="Primary" {{ old('school_type') == 'Primary' ? 'selected' : '' }}>
                                            Primary School</option>
                                        <option value="Secondary" {{ old('school_type') == 'Secondary' ? 'selected' : '' }}>Secondary School</option>
                                        <option value="High School" {{ old('school_type') == 'High School' ? 'selected' : '' }}>High School</option>
                                        <option value="University" {{ old('school_type') == 'University' ? 'selected' : '' }}>University</option>
                                    </select>
                                    @error('school_type')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div>
                                    <label for="school_address" class="block text-sm font-medium text-gray-300 mb-2">
                                        Address <span class="text-red-400">*</span>
                                    </label>
                                    <textarea name="school_address" id="school_address" rows="2" required
                                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Complete address with city">{{ old('school_address') }}</textarea>
                                    @error('school_address')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="school_phone" class="block text-sm font-medium text-gray-300 mb-2">
                                            School Phone <span class="text-red-400">*</span>
                                        </label>
                                        <input type="tel" name="school_phone" id="school_phone" required
                                            value="{{ old('school_phone') }}"
                                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="+229 XX XX XX XX">
                                        @error('school_phone')
                                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="school_email" class="block text-sm font-medium text-gray-300 mb-2">
                                            School Email <span class="text-red-400">*</span>
                                        </label>
                                        <input type="email" name="school_email" id="school_email" required
                                            value="{{ old('school_email') }}"
                                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="school@example.com">
                                        @error('school_email')
                                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Manager Information Section -->
                        <div>
                            <h2 class="text-2xl font-semibold text-white mb-4 pb-2 border-b border-gray-700">
                                School Manager Information
                            </h2>
                            <p class="text-sm text-gray-400 mb-4">This person will be the main administrator for your
                                school</p>
                            <div class="space-y-4">
                                <div>
                                    <label for="manager_name" class="block text-sm font-medium text-gray-300 mb-2">
                                        Full Name <span class="text-red-400">*</span>
                                    </label>
                                    <input type="text" name="manager_name" id="manager_name" required
                                        value="{{ old('manager_name') }}"
                                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                        placeholder="Manager's full name">
                                    @error('manager_name')
                                        <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                    @enderror
                                </div>

                                <div class="grid md:grid-cols-2 gap-4">
                                    <div>
                                        <label for="manager_email" class="block text-sm font-medium text-gray-300 mb-2">
                                            Email <span class="text-red-400">*</span>
                                        </label>
                                        <input type="email" name="manager_email" id="manager_email" required
                                            value="{{ old('manager_email') }}"
                                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="manager@example.com">
                                        @error('manager_email')
                                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>

                                    <div>
                                        <label for="manager_phone" class="block text-sm font-medium text-gray-300 mb-2">
                                            Phone <span class="text-red-400">*</span>
                                        </label>
                                        <input type="tel" name="manager_phone" id="manager_phone" required
                                            value="{{ old('manager_phone') }}"
                                            class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-blue-500 focus:border-transparent"
                                            placeholder="+229 XX XX XX XX">
                                        @error('manager_phone')
                                            <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                                        @enderror
                                    </div>
                                </div>

                                <div class="bg-blue-500/10 border border-blue-500/50 rounded-lg p-4">
                                    <p class="text-sm text-blue-300">
                                        <strong>Note:</strong> Login credentials will be sent to this email address
                                        after your school is approved by our admin.
                                    </p>
                                </div>
                            </div>
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
                                Submit Registration
                            </button>
                            <a href="{{ url('/') }}"
                                class="px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition text-center">
                                Cancel
                            </a>
                        </div>

                        <p class="text-sm text-gray-400 text-center">
                            Your registration will be reviewed by our admin team. You'll receive an email once it's
                            approved.
                        </p>
                    </form>
                </div>
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-gray-800 border-t border-gray-700 py-6 mt-12">
            <div class="max-w-7xl mx-auto px-4 text-center text-gray-400 text-sm">
                <p>&copy; {{ date('Y') }} EduTech Benin. All rights reserved.</p>
            </div>
        </footer>
    </div>
</body>

</html>