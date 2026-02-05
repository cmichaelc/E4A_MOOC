<?php

namespace App\Http\Controllers;

use App\Models\Student;
use App\Models\ClassModel;
use App\Models\AcademicTerm; // Added
use App\Models\Grade; // Added
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class ReportCardController extends Controller
{
    /**
     * Generate report card PDF for a student
     * @param int $studentId
     * @param int|null $termId - Optional academic term ID to filter grades
     */
    public function generate($studentId, $termId = null)
    {
        // Fetch student with relationships
        $student = Student::with([
            'user',
            'class.school',
            'class.classSubjects.subject',
            'class.classSubjects.teacher.user'
        ])->findOrFail($studentId);

        // Get selected term or default to current term
        $selectedTerm = null;
        if ($termId) {
            $selectedTerm = AcademicTerm::findOrFail($termId);
        } else {
            // Get current term for the school
            $selectedTerm = AcademicTerm::where('school_id', $student->class->school_id)
                ->where('is_current', true)
                ->first();
        }

        $classSubjects = $student->class->classSubjects;
        $subjectGrades = [];

        foreach ($classSubjects as $classSubject) {
            // Build grades query
            $gradesQuery = Grade::where('student_id', $student->id)
                ->where('class_subject_id', $classSubject->id);

            // Filter by term if specified
            if ($selectedTerm) {
                $gradesQuery->where('academic_term_id', $selectedTerm->id);
            }

            $grades = $gradesQuery->get();

            if ($grades->isEmpty()) {
                continue;
            }

            // Separate controls and exams
            $controls = $grades->where('type', 'Control');
            $exams = $grades->where('type', 'Exam');

            $avgControls = $controls->isNotEmpty() ? $controls->avg('score') : 0;
            $sumExams = $exams->sum('score');
            $nbExams = $exams->count();

            // Benin formula: ((avg_controls + sum_exams) / (nb_exams + 1)) * coefficient
            if ($nbExams == 0) {
                $finalGrade = $avgControls * $classSubject->coefficient;
            } else {
                $finalGrade = (($avgControls + $sumExams) / ($nbExams + 1)) * $classSubject->coefficient;
            }

            $subjectGrades[] = [
                'subject' => $classSubject->subject->name,
                'avg_controls' => $avgControls,
                'sum_exams' => $sumExams,
                'nb_exams' => $nbExams,
                'coefficient' => $classSubject->coefficient,
                'final_grade' => round($finalGrade, 2),
                'teacher' => $classSubject->teacher->user->name ?? 'N/A',
            ];
        }

        // Calculate overall average
        if (count($subjectGrades) > 0) {
            $totalWeighted = array_reduce($subjectGrades, function ($carry, $grade) {
                return $carry + $grade['final_grade'];
            }, 0);
            $overallAverage = $totalWeighted / count($subjectGrades);
        } else {
            $overallAverage = 0;
        }

        // Get attendance stats
        $attendanceStats = $this->getAttendanceStats($student);

        // Prepare data for PDF
        $academicYear = $student->class->academic_year ?? date('Y') . '-' . (date('Y') + 1);

        $data = [
            'student' => $student,
            'subjectGrades' => $subjectGrades,
            'overallAverage' => $overallAverage,
            'attendanceStats' => $attendanceStats,
            'academicYear' => $academicYear,
            'selectedTerm' => $selectedTerm,
            'generatedDate' => now()->format('F d, Y'),
        ];

        // Generate PDF
        $pdf = Pdf::loadView('reports.report-card', $data);
        $pdf->setPaper('a4', 'portrait');

        // Generate filename
        $termSuffix = $selectedTerm ? '_' . str_replace(' ', '_', $selectedTerm->name) : '';
        $filename = str_replace(' ', '_', $student->user->name) . '_Report_Card' . $termSuffix . '_' . date('Y') . '.pdf';

        return $pdf->download($filename);
    }

    /**
     * Preview report card in HTML before generating PDF
    public function preview($studentId)
    {
        $student = Student::with([
            'user',
            'class.school',
            'class.classSubjects.subject',
            'class.classSubjects.teacher.user',
            'grades'
        ])->findOrFail($studentId);

        // Calculate grades per subject
        $subjectGrades = [];
        $totalWeightedGrade = 0;
        $totalCoefficient = 0;

        foreach ($student->class->classSubjects as $classSubject) {
            $controls = $student->grades()
                ->where('class_subject_id', $classSubject->id)
                ->where('type', 'control')
                ->get();

            $exams = $student->grades()
                ->where('class_subject_id', $classSubject->id)
                ->where('type', 'exam')
                ->get();

            if ($controls->count() > 0 || $exams->count() > 0) {
                $avgControls = $controls->count() > 0 ? $controls->avg('score') : 0;
                $sumExams = $exams->sum('score');

                $finalGrade = (($avgControls + $sumExams) / 3) * $classSubject->coefficient;

                $subjectGrades[] = [
                    'subject' => $classSubject->subject->name,
                    'avg_controls' => round($avgControls, 2),
                    'sum_exams' => $sumExams,
                    'final_grade' => round($finalGrade, 2),
                    'coefficient' => $classSubject->coefficient,
                    'teacher' => $classSubject->teacher->user->name ?? 'N/A',
                ];

                $totalWeightedGrade += $finalGrade;
                $totalCoefficient += $classSubject->coefficient;
            }
        }

        $overallAverage = $totalCoefficient > 0 ? round($totalWeightedGrade / $totalCoefficient, 2) : 0;
        $attendanceStats = $this->getAttendanceStats($student);
        $academicYear = $student->class->academic_year ?? date('Y') . '-' . (date('Y') + 1);

        return view('reports.report-card', [
            'student' => $student,
            'subjectGrades' => $subjectGrades,
            'overallAverage' => $overallAverage,
            'attendanceStats' => $attendanceStats,
            'academicYear' => $academicYear,
            'generatedDate' => now()->format('F d, Y'),
            'isPreview' => true,
        ]);
    }

    /**
     * Get attendance statistics for a student
     */
    private function getAttendanceStats($student)
    {
        $attendanceRecords = $student->attendances;

        return [
            'total' => $attendanceRecords->count(),
            'present' => $attendanceRecords->where('status', 'present')->count(),
            'absent' => $attendanceRecords->where('status', 'absent')->count(),
            'late' => $attendanceRecords->where('status', 'late')->count(),
            'excused' => $attendanceRecords->where('status', 'excused')->count(),
            'percentage' => $attendanceRecords->count() > 0
                ? round(($attendanceRecords->where('status', 'present')->count() / $attendanceRecords->count()) * 100, 1)
                : 0,
        ];
    }
}
