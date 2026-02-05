<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ParentModel extends Model
{
    use HasFactory;

    protected $table = 'parents';
    protected $fillable = ['user_id', 'phone', 'address', 'occupation'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function students()
    {
        return $this->hasMany(Student::class, 'parent_id');
    }

    // Helper method to get all children's grades
    public function getChildrenGrades()
    {
        return $this->students->map(function ($student) {
            return [
                'student' => $student,
                'grades' => $student->grades()->with('classSubject.subject')->get()
            ];
        });
    }
}
