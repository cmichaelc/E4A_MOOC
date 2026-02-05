@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-white">Pending School Registrations</h1>
            <a href="{{ route('admin.schools') }}"
                class="px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition">
                View All Schools
            </a>
        </div>

        @if($pendingSchools->isEmpty())
            <div class="bg-gray-800 rounded-lg shadow-xl p-12 text-center">
                <div class="mx-auto w-16 h-16 bg-gray-700 rounded-full flex items-center justify-center mb-4">
                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z">
                        </path>
                    </svg>
                </div>
                <h3 class="text-xl font-semibold text-gray-300 mb-2">No Pending Registrations</h3>
                <p class="text-gray-400">All school registrations have been reviewed</p>
            </div>
        @else
            <div class="grid gap-6">
                @foreach($pendingSchools as $school)
                    <div class="bg-gray-800 rounded-lg shadow-xl p-6">
                        <div class="flex items-start justify-between mb-4">
                            <div>
                                <h3 class="text-2xl font-bold text-white mb-2">{{ $school->name }}</h3>
                                <p class="text-gray-400 flex items-center">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z">
                                        </path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    </svg>
                                    {{ $school->address ?? 'No address provided' }}
                                </p>
                            </div>
                            <span class="px-3 py-1 text-sm font-semibold rounded-full bg-yellow-500/20 text-yellow-400">
                                Pending
                            </span>
                        </div>

                        <div class="bg-gray-750 rounded-lg p-4 mb-4">
                            <h4 class="text-sm font-semibold text-gray-400 mb-2">School Manager</h4>
                            <div class="grid md:grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-500">Name</p>
                                    <p class="text-white font-medium">{{ $school->manager->name }}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-500">Email</p>
                                    <p class="text-white font-medium">{{ $school->manager->email }}</p>
                                </div>
                            </div>
                        </div>

                        <div class="text-sm text-gray-400 mb-4">
                            <p>Registered: {{ $school->created_at->diffForHumans() }}
                                ({{ $school->created_at->format('M d, Y H:i') }})</p>
                        </div>

                        <div class="flex gap-3">
                            <!-- Approve Button -->
                            <form method="POST" action="{{ route('admin.schools.approve', $school) }}" class="flex-1">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('Are you sure you want to approve {{ $school->name }}?')"
                                    class="w-full px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition flex items-center justify-center">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7">
                                        </path>
                                    </svg>
                                    Approve
                                </button>
                            </form>

                            <!-- Reject Button (Opens Modal) -->
                            <button onclick="openRejectModal({{ $school->id }}, '{{ $school->name }}')"
                                class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition flex items-center justify-center">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12">
                                    </path>
                                </svg>
                                Reject
                            </button>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Rejection Modal -->
    <div id="rejectModal" class="fixed inset-0 bg-black bg-opacity-50 hidden items-center justify-center z-50">
        <div class="bg-gray-800 rounded-lg shadow-xl max-w-md w-full mx-4 p-6">
            <h3 class="text-xl font-bold text-white mb-4">Reject School Registration</h3>
            <p class="text-gray-400 mb-4">School: <span id="schoolName" class="text-white font-semibold"></span></p>

            <form id="rejectForm" method="POST" action="">
                @csrf
                <div class="mb-4">
                    <label for="rejection_reason" class="block text-sm font-medium text-gray-300 mb-2">
                        Rejection Reason <span class="text-red-400">*</span>
                    </label>
                    <textarea name="rejection_reason" id="rejection_reason" rows="4" required
                        class="w-full px-4 py-2 bg-gray-700 border border-gray-600 rounded-lg text-white focus:ring-2 focus:ring-red-500 focus:border-transparent"
                        placeholder="Explain why this registration is being rejected..."></textarea>
                    <p class="mt-1 text-xs text-gray-400">This will be sent to the school manager so they can make
                        corrections.</p>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="closeRejectModal()"
                        class="flex-1 px-4 py-2 bg-gray-700 hover:bg-gray-600 text-white rounded-lg font-medium transition">
                        Cancel
                    </button>
                    <button type="submit"
                        class="flex-1 px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">
                        Confirm Rejection
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function openRejectModal(schoolId, schoolName) {
            document.getElementById('schoolName').textContent = schoolName;
            document.getElementById('rejectForm').action = `/admin/schools/${schoolId}/reject`;
            document.getElementById('rejectModal').classList.remove('hidden');
            document.getElementById('rejectModal').classList.add('flex');
        }

        function closeRejectModal() {
            document.getElementById('rejectModal').classList.add('hidden');
            document.getElementById('rejectModal').classList.remove('flex');
            document.getElementById('rejection_reason').value = '';
        }

        // Close modal on escape key
        document.addEventListener('keydown', function (e) {
            if (e.key === 'Escape') {
                closeRejectModal();
            }
        });
    </script>
@endsection