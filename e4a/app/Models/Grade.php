<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Grade extends Model
{
    use HasFactory;

    protected $fillable = [
        'student_id',
        'class_subject_id',
        'type',
        'score',
        'date',
        'academic_year'
    ];

    protected $casts = [
        'score' => 'decimal:2',
        'date' => 'date',
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function classSubject()
    {
        return $this->belongsTo(ClassSubject::class);
    }

    public function academicTerm()
    {
        return $this->belongsTo(AcademicTerm::class);
    }
    /**
     * Calculate final subject grade using Benin grading formula:
     * ((Avg_of_controls + Sum_of_exams) / (nb_of_exams + 1)) * coefficient
     *
     * @param int $studentId
     * @param int $classSubjectId
     * @return float|null
     */
    public static function calculateSubjectGrade($studentId, $classSubjectId)
    {
        $grades = self::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->get();

        if ($grades->isEmpty()) {
            return null;
        }

        $controls = $grades->where('type', 'Control');
        $exams = $grades->where('type', 'Exam');

        $avgControls = $controls->isNotEmpty() ? $controls->avg('score') : 0;
        $sumExams = $exams->sum('score');
        $nbExams = $exams->count();

        // Get coefficient from class_subject
        $classSubject = ClassSubject::find($classSubjectId);
        $coefficient = $classSubject ? $classSubject->coefficient : 1;

        // Formula: ((avg_controls + sum_exams) / (nb_exams + 1)) * coefficient
        if ($nbExams == 0) {
            // If no exams, just return weighted average of controls
            return $avgControls * $coefficient;
        }

        $finalGrade = (($avgControls + $sumExams) / ($nbExams + 1)) * $coefficient;

        return round($finalGrade, 2);
    }

    /**
     * Get grade breakdown for display
     */
    public static function getGradeBreakdown($studentId, $classSubjectId)
    {
        $grades = self::where('student_id', $studentId)
            ->where('class_subject_id', $classSubjectId)
            ->get();

        $controls = $grades->where('type', 'Control');
        $exams = $grades->where('type', 'Exam');

        $classSubject = ClassSubject::with('subject')->find($classSubjectId);

        return [
            'controls' => $controls,
            'exams' => $exams,
            'avg_controls' => $controls->isNotEmpty() ? $controls->avg('score') : 0,
            'sum_exams' => $exams->sum('score'),
            'nb_exams' => $exams->count(),
            'coefficient' => $classSubject->coefficient ?? 1,
            'final_grade' => self::calculateSubjectGrade($studentId, $classSubjectId),
            'subject' => $classSubject->subject ?? null,
        ];
    }
}
