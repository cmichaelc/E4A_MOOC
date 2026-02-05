<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Teacher extends Model
{
    use HasFactory;

    protected $fillable = ['user_id', 'specialization', 'phone'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function schools()
    {
        return $this->belongsToMany(School::class, 'school_teacher')
            ->withPivot('assigned_date')
            ->withTimestamps();
    }

    public function classSubjects()
    {
        return $this->hasMany(ClassSubject::class);
    }

    // Get all classes this teacher teaches
    public function classes()
    {
        return $this->hasManyThrough(
            ClassModel::class,
            ClassSubject::class,
            'teacher_id', // Foreign key on class_subject
            'id',         // Foreign key on classes
            'id',         // Local key on teachers
            'class_id'    // Local key on class_subject
        )->distinct();
    }
}
