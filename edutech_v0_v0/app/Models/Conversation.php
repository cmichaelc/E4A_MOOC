<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'teacher_id',
        'parent_id',
        'student_id',
        'last_message_at',
    ];

    protected $casts = [
        'last_message_at' => 'datetime',
    ];

    /**
     * Get the teacher in this conversation
     */
    public function teacher()
    {
        return $this->belongsTo(Teacher::class);
    }

    /**
     * Get the parent in this conversation
     */
    public function parent()
    {
        return $this->belongsTo(ParentModel::class, 'parent_id');
    }

    /**
     * Get the student this conversation is about
     */
    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    /**
     * Get all messages in this conversation
     */
    public function messages()
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    /**
     * Get count of unread messages for a user
     */
    public function unreadCountFor($userId)
    {
        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->whereNull('read_at')
            ->count();
    }

    /**
     * Get the other participant (not the current user)
     */
    public function getOtherParticipant($currentUser)
    {
        if ($currentUser->isTeacher()) {
            return $this->parent->user;
        } else {
            return $this->teacher->user;
        }
    }
}
