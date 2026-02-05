<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class School extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'status',
        'address',
        'manager_id',
        'rejection_reason',
        'reviewed_at',
        'reviewed_by',
    ];

    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }

    public function classes()
    {
        return $this->hasMany(ClassModel::class);
    }

    public function teachers()
    {
        return $this->belongsToMany(Teacher::class, 'school_teacher')
            ->withPivot('assigned_date')
            ->withTimestamps();
    }
}
