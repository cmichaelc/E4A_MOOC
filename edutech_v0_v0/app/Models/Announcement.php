<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Announcement extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'author_id',
        'title',
        'content',
        'target',
        'target_class_id',
        'priority',
        'published_at',
    ];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    /**
     * Get the school this announcement belongs to
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get the author of this announcement
     */
    public function author()
    {
        return $this->belongsTo(User::class, 'author_id');
    }

    /**
     * Get the target class (if specific class)
     */
    public function targetClass()
    {
        return $this->belongsTo(ClassModel::class, 'target_class_id');
    }

    /**
     * Scope for published announcements
     */
    public function scopePublished($query)
    {
        return $query->whereNotNull('published_at')
            ->where('published_at', '<=', now());
    }

    /**
     * Check if announcement is visible to a user
     */
    public function isVisibleTo($user)
    {
        // Admin can see all
        if ($user->isAdmin()) {
            return true;
        }

        // Must be from same school
        if ($user->isStudent() && $user->student) {
            $userSchoolId = $user->student->class->school_id;
        } elseif ($user->isParent() && $user->parentModel) {
            $student = $user->parentModel->students()->first();
            $userSchoolId = $student ? $student->class->school_id : null;
        } elseif ($user->isTeacher() && $user->teacher) {
            $school = $user->teacher->schools()->first();
            $userSchoolId = $school ? $school->id : null;
        } elseif ($user->isManager() && $user->manager) {
            $userSchoolId = $user->manager->school_id;
        } else {
            return false;
        }

        if ($this->school_id !== $userSchoolId) {
            return false;
        }

        // Check if targeted
        if ($this->target === 'all') {
            return true;
        }

        // Specific class targeting
        if ($this->target === 'specific_class') {
            if ($user->isStudent() && $user->student) {
                return $user->student->class_id === $this->target_class_id;
            } elseif ($user->isParent() && $user->parentModel) {
                return $user->parentModel->students()
                    ->where('class_id', $this->target_class_id)
                    ->exists();
            }
        }

        return false;
    }

    /**
     * Get priority badge color
     */
    public function getPriorityColorAttribute()
    {
        return match ($this->priority) {
            'urgent' => 'red',
            'high' => 'yellow',
            'normal' => 'blue',
            'low' => 'gray',
            default => 'gray',
        };
    }
}
