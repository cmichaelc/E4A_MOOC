<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="dark">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registration Successful - E4A_MOOC Benin</title>
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans antialiased bg-gray-900 text-gray-100">
    <div class="min-h-screen flex items-center justify-center px-4">
        <div class="max-w-2xl w-full">
            <!-- Success Icon -->
            <div class="text-center mb-8">
                <div class="inline-flex items-center justify-center w-20 h-20 bg-green-500/20 rounded-full mb-4">
                    <svg class="w-10 h-10 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                </div>
                <h1 class="text-4xl font-bold text-white mb-4">Registration Submitted!</h1>
                <p class="text-xl text-gray-400">Your school registration is under review</p>
            </div>

            <!-- Status Card -->
            <div class="bg-gray-800 rounded-lg shadow-xl p-8 mb-6">
                <div class="space-y-6">
                    <div class="bg-blue-500/10 border border-blue-500/50 rounded-lg p-4">
                        <h2 class="text-lg font-semibold text-blue-400 mb-2">✉️ Check Your Email</h2>
                        <p class="text-gray-300">
                            We've sent your login credentials to the email address you provided.
                            Please check your inbox (and spam folder).
                        </p>
                    </div>

                    <div>
                        <h3 class="text-xl font-semibold text-white mb-4">What Happens Next?</h3>
                        <div class="space-y-3">
                            <div class="flex items-start space-x-3">
                                <div
                                    class="flex-shrink-0 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                    1</div>
                                <div>
                                    <p class="text-gray-300">
                                        <strong class="text-white">Admin Review:</strong> Our team will review your
                                        school registration details.
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <div
                                    class="flex-shrink-0 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                    2</div>
                                <div>
                                    <p class="text-gray-300">
                                        <strong class="text-white">Approval Notification:</strong> You'll receive an
                                        email once your school is approved or if we need more information.
                                    </p>
                                </div>
                            </div>

                            <div class="flex items-start space-x-3">
                                <div
                                    class="flex-shrink-0 w-6 h-6 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-bold">
                                    3</div>
                                <div>
                                    <p class="text-gray-300">
                                        <strong class="text-white">Start Using E4A_MOOC:</strong> After approval, log in
                                        and begin setting up your school!
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="bg-yellow-500/10 border border-yellow-500/50 rounded-lg p-4">
                        <h3 class="text-yellow-400 font-semibold mb-2">⏱️ Review Time</h3>
                        <p class="text-gray-300 text-sm">
                            Typically, registrations are reviewed within 1-2 business days.
                            We'll contact you as soon as possible.
                        </p>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="flex flex-col sm:flex-row gap-4">
                <a href="{{ url('/') }}"
                    class="flex-1 px-6 py-3 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium text-center transition">
                    Back to Home
                </a>
                <a href="{{ route('login') }}"
                    class="flex-1 px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium text-center transition">
                    Go to Login
                </a>
            </div>

            <!-- Support -->
            <div class="mt-8 text-center">
                <p class="text-gray-400 text-sm">
                    Questions? Contact us at
                    <a href="mailto:support@E4A_MOOC.bj" class="text-blue-400 hover:text-blue-300 underline">
                        support@E4A_MOOC.bj
                    </a>
                </p>
            </div>
        </div>
    </div>
</body>

</html>