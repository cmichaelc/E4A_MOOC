@extends('layouts.app')

@section('content')
    <div class="max-w-4xl mx-auto space-y-6">
        <!-- Header -->
        <div class="bg-gray-800 rounded-lg shadow-xl p-6">
            <div class="flex items-center justify-between">
                <div>
                    <a href="{{ auth()->user()->isTeacher() ? route('teacher.messages') : route('parent.messages') }}"
                        class="text-blue-400 hover:text-blue-300 text-sm mb-2 inline-block">
                        ← Back to Messages
                    </a>
                    <h1 class="text-2xl font-bold text-white">Conversation about {{ $conversation->student->user->name }}
                    </h1>
                    <p class="text-gray-400 mt-1">
                        Between {{ $conversation->teacher->user->name }} and {{ $conversation->parent->user->name }}
                    </p>
                </div>
            </div>
        </div>

        <!-- Messages -->
        <div class="bg-gray-800 rounded-lg shadow-xl p-6">
            <div class="space-y-4 mb-6" id="messagesContainer" style="max-height: 500px; overflow-y: auto;">
                @forelse($conversation->messages as $message)
                    @php
                        $isMe = $message->sender_id === auth()->id();
                    @endphp

                    <div class="flex {{ $isMe ? 'justify-end' : 'justify-start' }}">
                        <div class="max-w-md">
                            <div class="flex items-start space-x-2 {{ $isMe ? 'flex-row-reverse space-x-reverse' : '' }}">
                                <!-- Avatar -->
                                <div
                                    class="w-8 h-8 {{ $isMe ? 'bg-blue-600' : 'bg-gray-600' }} rounded-full flex items-center justify-center text-white text-sm font-semibold flex-shrink-0">
                                    {{ substr($message->sender->name, 0, 1) }}
                                </div>

                                <!-- Message bubble -->
                                <div>
                                    <div
                                        class="px-4 py-3 rounded-lg {{ $isMe ? 'bg-blue-600 text-white' : 'bg-gray-700 text-gray-100' }}">
                                        <p class="text-sm whitespace-pre-wrap">{{ $message->message }}</p>
                                    </div>
                                    <p class="text-xs text-gray-500 mt-1 {{ $isMe ? 'text-right' : '' }}">
                                        {{ $message->created_at->format('M d, h:i A') }}
                                        @if($message->read_at)
                                            <span class="text-gray-600">· Read</span>
                                        @endif
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="text-center py-8">
                        <p class="text-gray-400">No messages yet. Start the conversation!</p>
                    </div>
                @endforelse
            </div>

            <!-- Reply Form -->
            <form method="POST"
                action="{{ auth()->user()->isTeacher() ? route('teacher.messages.send') : route('parent.messages.send') }}"
                class="border-t border-gray-700 pt-4">
                @csrf
                <input type="hidden" name="conversation_id" value="{{ $conversation->id }}">
                <input type="hidden" name="student_id" value="{{ $conversation->student_id }}">

                <div class="flex space-x-3">
                    <textarea name="message" rows="3" required placeholder="Type your message..."
                        class="flex-1 px-4 py-3 bg-gray-700 border border-gray-600 rounded-lg text-white placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>

                    <button type="submit"
                        class="px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition self-end">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path>
                        </svg>
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        // Auto-scroll to bottom on page load
        document.addEventListener('DOMContentLoaded', function () {
            const container = document.getElementById('messagesContainer');
            if (container) {
                container.scrollTop = container.scrollHeight;
            }
        });
    </script>
@endsection