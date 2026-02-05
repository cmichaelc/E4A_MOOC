@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white">Messages</h1>
                <p class="text-gray-400 mt-1">Messages from your children's teachers</p>
            </div>
        </div>

        @if($conversations->isEmpty())
            <div class="bg-gray-800 rounded-lg shadow-xl p-12 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                    </path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-300 mb-2">No Messages Yet</h3>
                <p class="text-gray-400">Your messages will appear here</p>
            </div>
        @else
            @foreach($conversationsByStudent as $studentId => $studentConversations)
                @php
                    $student = $studentConversations->first()->student;
                @endphp

                <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden">
                    <div class="px-6 py-4 bg-gray-750 border-b border-gray-700">
                        <h3 class="text-lg font-bold text-white">{{ $student->user->name }}</h3>
                        <p class="text-sm text-gray-400">{{ $student->class->name }}</p>
                    </div>

                    <div class="divide-y divide-gray-700">
                        @foreach($studentConversations as $conversation)
                            <a href="{{ route('parent.messages.show', $conversation->id) }}"
                                class="block p-4 hover:bg-gray-750 transition {{ $conversation->unread_count > 0 ? 'bg-blue-900/20' : '' }}">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4 flex-1">
                                        <!-- Avatar -->
                                        <div
                                            class="w-12 h-12 bg-green-600 rounded-full flex items-center justify-center text-white text-lg font-bold">
                                            {{ substr($conversation->teacher->user->name, 0, 1) }}
                                        </div>

                                        <!-- Content -->
                                        <div class="flex-1">
                                            <span class="text-white font-semibold">{{ $conversation->teacher->user->name }}</span>
                                            <p class="text-gray-500 text-sm">Teacher</p>

                                            @if($conversation->messages->isNotEmpty())
                                                <p class="text-gray-400 text-sm mt-1 truncate">
                                                    {{ Str::limit($conversation->messages->last()->message, 60) }}
                                                </p>
                                            @endif
                                        </div>
                                    </div>

                                    <!-- Right side -->
                                    <div class="flex items-center space-x-3">
                                        @if($conversation->last_message_at)
                                            <span class="text-gray-500 text-xs">
                                                {{ $conversation->last_message_at->diffForHumans() }}
                                            </span>
                                        @endif

                                        @if($conversation->unread_count > 0)
                                            <span class="px-2 py-1 bg-blue-600 text-white rounded-full text-xs font-semibold">
                                                {{ $conversation->unread_count }}
                                            </span>
                                        @endif
                                    </div>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endforeach
        @endif
    </div>
@endsection