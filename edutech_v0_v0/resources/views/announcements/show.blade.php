@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <a href="{{ route('announcements.index') }}" class="text-blue-400 hover:text-blue-300 inline-block mb-4">
            ← Back to Announcements
        </a>

        <div class="bg-gray-800 rounded-lg shadow-xl p-8 border-l-4 
            {{ $announcement->priority === 'urgent' ? 'border-red-500' :
        ($announcement->priority === 'high' ? 'border-yellow-500' :
            ($announcement->priority === 'normal' ? 'border-blue-500' : 'border-gray-600')) }}">

            <!-- Header -->
            <div class="mb-6">
                <div class="flex items-center space-x-3 mb-2">
                    <h1 class="text-3xl font-bold text-white">{{ $announcement->title }}</h1>
                    <span
                        class="px-3 py-1 rounded text-sm font-semibold 
                        {{ $announcement->priority === 'urgent' ? 'bg-red-500/20 text-red-400' :
        ($announcement->priority === 'high' ? 'bg-yellow-500/20 text-yellow-400' :
            ($announcement->priority === 'normal' ? 'bg-blue-500/20 text-blue-400' : 'bg-gray-500/20 text-gray-400')) }}">
                        {{ ucfirst($announcement->priority) }} Priority
                    </span>
                </div>

                <div class="flex items-center space-x-4 text-sm text-gray-400">
                    <div class="flex items-center space-x-2">
                        <div
                            class="w-8 h-8 bg-blue-600 rounded-full flex items-center justify-center text-white text-sm font-semibold">
                            {{ substr($announcement->author->name, 0, 1) }}
                        </div>
                        <span>{{ $announcement->author->name }}</span>
                    </div>
                    <span>•</span>
                    <span>{{ $announcement->published_at->format('F d, Y \a\t h:i A') }}</span>
                    @if($announcement->target === 'specific_class')
                        <span>•</span>
                        <span class="px-2 py-1 bg-blue-500/20 text-blue-400 rounded text-xs">
                            {{ $announcement->targetClass->name }} only
                        </span>
                    @else
                        <span>•</span>
                        <span class="px-2 py-1 bg-green-500/20 text-green-400 rounded text-xs">
                            Whole School
                        </span>
                    @endif
                </div>
            </div>

            <!-- Content -->
            <div class="prose prose-invert max-w-none">
                <div class="text-gray-200 whitespace-pre-wrap leading-relaxed">{{ $announcement->content }}</div>
            </div>

            <!-- Actions (if owner or manager) -->
            @if($announcement->author_id === auth()->id() || auth()->user()->isManager())
                <div class="mt-8 pt-6 border-t border-gray-700 flex space-x-3">
                    <a href="{{ route('announcements.edit', $announcement->id) }}"
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                        Edit
                    </a>
                    <form method="POST" action="{{ route('announcements.destroy', $announcement->id) }}" class="inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" onclick="return confirm('Delete this announcement?')"
                            class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg font-medium transition">
                            Delete
                        </button>
                    </form>
                </div>
            @endif
        </div>
    </div>
@endsection