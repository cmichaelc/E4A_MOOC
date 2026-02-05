<?php

namespace App\Http\Controllers;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\Student;
use App\Models\Teacher;
use App\Models\ParentModel;
use Illuminate\Http\Request;

class MessageController extends Controller
{
    /**
     * Show teacher's inbox with all conversations
     */
    public function teacherInbox()
    {
        $teacher = auth()->user()->teacher;

        if (!$teacher) {
            return redirect()->back()->with('error', 'Teacher profile not found.');
        }

        // Get all conversations for this teacher, ordered by last message
        $conversations = Conversation::where('teacher_id', $teacher->id)
            ->with([
                'parent.user',
                'student.user',
                'messages' => function ($query) {
                    $query->latest()->limit(1); // Get last message for preview
                }
            ])
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Get unread counts
        foreach ($conversations as $conversation) {
            $conversation->unread_count = $conversation->unreadCountFor(auth()->id());
        }

        return view('teacher.messages.inbox', compact('conversations'));
    }

    /**
     * Show parent's inbox with all conversations
     */
    public function parentInbox()
    {
        $parent = auth()->user()->parentModel;

        if (!$parent) {
            return redirect()->back()->with('error', 'Parent profile not found.');
        }

        // Get all conversations for this parent
        $conversations = Conversation::where('parent_id', $parent->id)
            ->with([
                'teacher.user',
                'student.user',
                'messages' => function ($query) {
                    $query->latest()->limit(1);
                }
            ])
            ->orderBy('last_message_at', 'desc')
            ->get();

        // Group by student for better organization
        $conversationsByStudent = $conversations->groupBy('student_id');

        foreach ($conversations as $conversation) {
            $conversation->unread_count = $conversation->unreadCountFor(auth()->id());
        }

        return view('parent.messages.inbox', compact('conversationsByStudent', 'conversations'));
    }

    /**
     * Show form to start new conversation (Teacher)
     */
    public function newConversation()
    {
        $teacher = auth()->user()->teacher;

        // Get all students in classes this teacher teaches
        $students = Student::whereHas('class.classSubjects', function ($query) use ($teacher) {
            $query->where('teacher_id', $teacher->id);
        })->with(['user', 'parent.user'])->get();

        return view('teacher.messages.new', compact('students'));
    }

    /**
     * Show form to message teacher (Parent)
     */
    public function newConversationParent($studentId)
    {
        $parent = auth()->user()->parentModel;
        $student = Student::findOrFail($studentId);

        // Verify this is parent's child
        if ($student->parent_id !== $parent->id) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        // Get teachers assigned to this student's class
        $teachers = Teacher::whereHas('schools', function ($query) use ($student) {
            $query->where('school_id', $student->class->school_id);
        })->with('user')->get();

        return view('parent.messages.new', compact('student', 'teachers'));
    }

    /**
     * Show conversation (Teacher)
     */
    public function teacherConversation($conversationId)
    {
        $conversation = Conversation::with([
            'messages.sender',
            'parent.user',
            'student.user',
            'teacher.user'
        ])->findOrFail($conversationId);

        // Verify teacher owns this conversation
        if ($conversation->teacher_id !== auth()->user()->teacher->id) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        // Mark all received messages as read
        $conversation->messages()
            ->where('sender_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('messages.conversation', compact('conversation'));
    }

    /**
     * Show conversation (Parent)
     */
    public function parentConversation($conversationId)
    {
        $conversation = Conversation::with([
            'messages.sender',
            'parent.user',
            'student.user',
            'teacher.user'
        ])->findOrFail($conversationId);

        // Verify parent owns this conversation
        if ($conversation->parent_id !== auth()->user()->parentModel->id) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        // Mark all received messages as read
        $conversation->messages()
            ->where('sender_id', '!=', auth()->id())
            ->whereNull('read_at')
            ->update(['read_at' => now()]);

        return view('messages.conversation', compact('conversation'));
    }

    /**
     * Send a message
     */
    public function sendMessage(Request $request)
    {
        $validated = $request->validate([
            'conversation_id' => 'nullable|exists:conversations,id',
            'student_id' => 'required_without:conversation_id|exists:students,id',
            'recipient_teacher_id' => 'required_if:conversation_id,null|exists:teachers,id',
            'recipient_parent_id' => 'required_if:conversation_id,null|exists:parents,id',
            'message' => 'required|string|max:5000',
        ]);

        $user = auth()->user();

        // If conversation_id exists, use it; otherwise create new conversation
        if ($request->conversation_id) {
            $conversation = Conversation::findOrFail($request->conversation_id);
        } else {
            // Create new conversation
            if ($user->isTeacher()) {
                $student = Student::findOrFail($validated['student_id']);
                $conversation = Conversation::firstOrCreate([
                    'teacher_id' => $user->teacher->id,
                    'parent_id' => $student->parent_id,
                    'student_id' => $student->id,
                ]);
            } else { // Parent
                $conversation = Conversation::firstOrCreate([
                    'teacher_id' => $validated['recipient_teacher_id'],
                    'parent_id' => $user->parentModel->id,
                    'student_id' => $validated['student_id'],
                ]);
            }
        }

        // Create the message
        $message = Message::create([
            'conversation_id' => $conversation->id,
            'sender_id' => $user->id,
            'message' => $validated['message'],
        ]);

        // Update conversation last_message_at
        $conversation->update(['last_message_at' => now()]);

        // Send notification to the other participant
        $otherParticipant = $conversation->getOtherParticipant($user);
        $otherParticipant->notify(new \App\Notifications\NewMessageNotification($message, $conversation));

        // Redirect to conversation
        if ($user->isTeacher()) {
            return redirect()->route('teacher.messages.show', $conversation->id)
                ->with('success', 'Message sent successfully!');
        } else {
            return redirect()->route('parent.messages.show', $conversation->id)
                ->with('success', 'Message sent successfully!');
        }
    }
}
