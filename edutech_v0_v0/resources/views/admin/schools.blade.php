@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <!-- Welcome Header -->
        <div class="bg-gradient-to-r from-red-600 to-red-800 rounded-lg shadow-xl p-6">
            <h1 class="text-3xl font-bold text-white">System Administration</h1>
            <p class="text-red-100 mt-1">Manage schools and system settings</p>
        </div>

        <!-- Quick Stats -->
        <div class="grid md:grid-cols-4 gap-4">
            <div class="bg-gray-800 rounded-lg shadow-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Total Schools</p>
                        <p class="text-3xl font-bold text-white">{{ $schools->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg shadow-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Active</p>
                        <p class="text-3xl font-bold text-green-400">{{ $schools->where('status', 'Active')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-green-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <div class="bg-gray-800 rounded-lg shadow-xl p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Pending</p>
                        <p class="text-3xl font-bold text-yellow-400">{{ $schools->where('status', 'Pending')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-600 rounded-lg flex items-center justify-center">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                </div>
            </div>

            <a href="{{ route('admin.schools.pending') }}" class="bg-gray-800 hover:bg-gray-750 rounded-lg shadow-xl p-6 transition group">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-sm text-gray-400">Approvals</p>
                        <p class="text-3xl font-bold text-red-400">{{ $schools->where('status', 'Pending')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-red-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition">
                        <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                    </div>
                </div>
            </a>
        </div>

        <div class="flex justify-between items-center">
            <h1 class="text-3xl font-bold text-white">Schools Management</h1>
            <a href="{{ route('admin.schools.create') }}"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                Create New School
            </a>
        </div>

        <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden">
            <table class="min-w-full divide-y divide-gray-700">
                <thead class="bg-gray-900">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Name</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Status
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Manager
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-400 uppercase tracking-wider">Address
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-gray-800 divide-y divide-gray-700">
                    @forelse($schools as $school)
                        <tr class="hover:bg-gray-750 transition">
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-white">
                                {{ $school->name }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span
                                    class="px-2 py-1 text-xs font-semibold rounded-full {{ $school->status === 'Active' ? 'bg-green-500/20 text-green-400' : 'bg-yellow-500/20 text-yellow-400' }}">
                                    {{ $school->status }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ $school->manager?->name ?? 'Not Assigned' }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-300">
                                {{ $school->address ?? 'N/A' }}
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="px-6 py-8 text-center text-gray-400">
                                No schools found. Create one to get started.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
@endsection