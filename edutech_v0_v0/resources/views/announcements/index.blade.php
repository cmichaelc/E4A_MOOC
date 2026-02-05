@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white">Announcements</h1>
                <p class="text-gray-400 mt-1">School news and updates</p>
            </div>
            @if(auth()->user()->isManager() || auth()->user()->isTeacher())
                <a href="{{ route('announcements.create') }}"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                    New Announcement
                </a>
            @endif
        </div>

        @if($announcements->isEmpty())
            <div class="bg-gray-800 rounded-lg shadow-xl p-12 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z">
                    </path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-300 mb-2">No Announcements</h3>
                <p class="text-gray-400">Announcements will appear here when posted</p>
            </div>
        @else
            <div class="space-y-4">
                @foreach($announcements as $announcement)
                    <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden border-l-4 
                                {{ $announcement->priority === 'urgent' ? 'border-red-500' :
                        ($announcement->priority === 'high' ? 'border-yellow-500' :
                            ($announcement->priority === 'normal' ? 'border-blue-500' : 'border-gray-600')) }}">
                        <div class="p-6">
                            <div class="flex items-start justify-between">
                                <div class="flex-1">
                                    <!-- Header -->
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-xl font-bold text-white">{{ $announcement->title }}</h3>
                                        <span
                                            class="px-2 py-1 rounded text-xs font-semibold 
                                                    {{ $announcement->priority === 'urgent' ? 'bg-red-500/20 text-red-400' :
                        ($announcement->priority === 'high' ? 'bg-yellow-500/20 text-yellow-400' :
                            ($announcement->priority === 'normal' ? 'bg-blue-500/20 text-blue-400' : 'bg-gray-500/20 text-gray-400')) }}">
                                            {{ ucfirst($announcement->priority) }}
                                        </span>
                                    </div>

                                    <!-- Meta -->
                                    <div class="flex items-center space-x-4 text-sm text-gray-400 mb-3">
                                        <span>{{ $announcement->author->name }}</span>
                                        <span>•</span>
                                        <span>{{ $announcement->published_at->format('M d, Y') }}</span>
                                        @if($announcement->target === 'specific_class')
                                            <span>•</span>
                                            <span class="text-blue-400">{{ $announcement->targetClass->name }}</span>
                                        @endif
                                    </div>

                                    <!-- Content Preview -->
                                    <p class="text-gray-300 mb-4">{{ Str::limit($announcement->content, 200) }}</p>

                                    <!-- Actions -->
                                    <div class="flex items-center space-x-3">
                                        <a href="{{ route('announcements.show', $announcement->id) }}"
                                            class="text-blue-400 hover:text-blue-300 text-sm font-medium">
                                            Read More →
                                        </a>

                                        @if($announcement->author_id === auth()->id() || auth()->user()->isManager())
                                            <a href="{{ route('announcements.edit', $announcement->id) }}"
                                                class="text-gray-400 hover:text-gray-300 text-sm">
                                                Edit
                                            </a>
                                            <form method="POST" action="{{ route('announcements.destroy', $announcement->id) }}"
                                                class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" onclick="return confirm('Delete this announcement?')"
                                                    class="text-red-400 hover:text-red-300 text-sm">
                                                    Delete
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
@endsection