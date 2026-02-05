@extends('layouts.app')

@section('content')
    <div class="space-y-6">
        <div class="flex justify-between items-center">
            <div>
                <h1 class="text-3xl font-bold text-white">Messages</h1>
                <p class="text-gray-400 mt-1">Communicate with parents about students</p>
            </div>
            <a href="{{ route('teacher.messages.new') }}"
                class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                New Message
            </a>
        </div>

        @if($conversations->isEmpty())
            <div class="bg-gray-800 rounded-lg shadow-xl p-12 text-center">
                <svg class="w-16 h-16 mx-auto mb-4 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M8 10h.01M12 10h.01M16 10h.01M9 16H5a2 2 0 01-2-2V6a2 2 0 012-2h14a2 2 0 012 2v8a2 2 0 01-2 2h-5l-5 5v-5z">
                    </path>
                </svg>
                <h3 class="text-xl font-semibold text-gray-300 mb-2">No Conversations Yet</h3>
                <p class="text-gray-400 mb-4">Start a conversation with a parent</p>
                <a href="{{ route('teacher.messages.new') }}"
                    class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                    New Message
                </a>
            </div>
        @else
            <div class="bg-gray-800 rounded-lg shadow-xl overflow-hidden">
                <div class="divide-y divide-gray-700">
                    @foreach($conversations as $conversation)
                        <a href="{{ route('teacher.messages.show', $conversation->id) }}"
                            class="block p-4 hover:bg-gray-750 transition {{ $conversation->unread_count > 0 ? 'bg-blue-900/20' : '' }}">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center space-x-4 flex-1">
                                    <!-- Avatar -->
                                    <div
                                        class="w-12 h-12 bg-purple-600 rounded-full flex items-center justify-center text-white text-lg font-bold">
                                        {{ substr($conversation->parent->user->name, 0, 1) }}
                                    </div>

                                    <!-- Content -->
                                    <div class="flex-1">
                                        <div class="flex items-center space-x-2">
                                            <span class="text-white font-semibold">{{ $conversation->parent->user->name }}</span>
                                            <span class="text-gray-500 text-sm">about</span>
                                            <span class="text-blue-400 text-sm">{{ $conversation->student->user->name }}</span>
                                        </div>

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
        @endif
    </div>
@endsection