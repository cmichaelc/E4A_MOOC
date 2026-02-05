<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class AcademicTerm extends Model
{
    use HasFactory;

    protected $fillable = [
        'school_id',
        'name',
        'academic_year',
        'start_date',
        'end_date',
        'is_current',
        'order',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
        'is_current' => 'boolean',
    ];

    /**
     * Get the school that owns this term
     */
    public function school()
    {
        return $this->belongsTo(School::class);
    }

    /**
     * Get all grades for this term
     */
    public function grades()
    {
        return $this->hasMany(Grade::class);
    }

    /**
     * Scope to get current term for a school
     */
    public function scopeCurrent($query, $schoolId)
    {
        return $query->where('school_id', $schoolId)->where('is_current', true);
    }

    /**
     * Scope to get terms for specific academic year
     */
    public function scopeForYear($query, $academicYear)
    {
        return $query->where('academic_year', $academicYear)->orderBy('order');
    }
}
