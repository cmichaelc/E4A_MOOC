<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Report Card - {{ $student->user->name }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'DejaVu Sans', Arial, sans-serif;
            font-size: 12px;
            color: #333;
            line-height: 1.6;
        }

        .container {
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            text-align: center;
            border-bottom: 3px solid #2563eb;
            padding-bottom: 20px;
            margin-bottom: 30px;
        }

        .school-name {
            font-size: 24px;
            font-weight: bold;
            color: #1e40af;
            margin-bottom: 5px;
        }

        .report-title {
            font-size: 20px;
            font-weight: bold;
            margin: 10px 0;
            color: #374151;
        }

        .student-info {
            background: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .info-row {
            display: flex;
            margin-bottom: 8px;
        }

        .info-label {
            font-weight: bold;
            width: 150px;
            color: #4b5563;
        }

        .info-value {
            color: #1f2937;
        }

        .grades-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 25px;
        }

        .grades-table th {
            background: #1e40af;
            color: white;
            padding: 10px;
            text-align: left;
            font-size: 11px;
            font-weight: bold;
        }

        .grades-table td {
            padding: 10px;
            border-bottom: 1px solid #e5e7eb;
        }

        .grades-table tr:nth-child(even) {
            background: #f9fafb;
        }

        .overall-average {
            background: #dbeafe;
            border: 2px solid #2563eb;
            padding: 20px;
            text-align: center;
            border-radius: 8px;
            margin-bottom: 25px;
        }

        .average-label {
            font-size: 14px;
            color: #1e40af;
            font-weight: bold;
            margin-bottom: 5px;
        }

        .average-value {
            font-size: 36px;
            font-weight: bold;
            color: #1e40af;
        }

        .attendance-section {
            border: 1px solid #d1d5db;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 25px;
        }

        .section-title {
            font-size: 16px;
            font-weight: bold;
            color: #1f2937;
            margin-bottom: 12px;
            border-bottom: 2px solid #2563eb;
            padding-bottom: 5px;
        }

        .attendance-stats {
            display: flex;
            justify-content: space-around;
            flex-wrap: wrap;
        }

        .stat-item {
            text-align: center;
            padding: 10px;
            flex: 1;
            min-width: 100px;
        }

        .stat-label {
            font-size: 10px;
            color: #6b7280;
            text-transform: uppercase;
        }

        .stat-value {
            font-size: 18px;
            font-weight: bold;
            color: #1f2937;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #d1d5db;
            text-align: center;
            color: #6b7280;
            font-size: 10px;
        }

        .signature-section {
            display: flex;
            justify-content: space-between;
            margin-top: 40px;
        }

        .signature-box {
            text-align: center;
            width: 45%;
            border-top: 1px solid #9ca3af;
            padding-top: 10px;
        }

        .grade-excellent {
            color: #059669;
            font-weight: bold;
        }

        .grade-good {
            color: #2563eb;
        }

        .grade-average {
            color: #d97706;
        }

        .grade-poor {
            color: #dc2626;
        }

        @media print {
            body {
                print-color-adjust: exact;
                -webkit-print-color-adjust: exact;
            }
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <div class="school-name">{{ $student->class->school->name }}</div>
            <div style="color: #6b7280; font-size: 12px;">{{ $student->class->school->address ?? '' }}</div>
            <div class="report-title">ACADEMIC REPORT CARD</div>
            <div style="color: #6b7280; font-size: 12px;">
                Academic Year {{ $academicYear }}
                @if($selectedTerm)
                    <span style="font-weight: bold; color: #2563eb;"> - {{ $selectedTerm->name }}</span>
                @endif
            </div>
        </div>

        <!-- Student Information -->
        <div class="student-info">
            <div class="info-row">
                <div class="info-label">Student Name:</div>
                <div class="info-value">{{ $student->user->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Class:</div>
                <div class="info-value">{{ $student->class->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Academic Year:</div>
                <div class="info-value">{{ $academicYear }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Date Issued:</div>
                <div class="info-value">{{ $generatedDate }}</div>
            </div>
        </div>

        <!-- Overall Average -->
        <div class="overall-average">
            <div class="average-label">OVERALL AVERAGE</div>
            <div class="average-value">{{ number_format($overallAverage, 2) }}/20</div>
        </div>

        <!-- Grades Table -->
        <table class="grades-table">
            <thead>
                <tr>
                    <th>Subject</th>
                    <th style="text-align: center;">Controls<br>Avg</th>
                    <th style="text-align: center;">Exams<br>Sum</th>
                    <th style="text-align: center;">Coef.</th>
                    <th style="text-align: center;">Final<br>Grade</th>
                    <th>Teacher</th>
                </tr>
            </thead>
            <tbody>
                @forelse($subjectGrades as $grade)
                    <tr>
                        <td><strong>{{ $grade['subject'] }}</strong></td>
                        <td style="text-align: center;">{{ number_format($grade['avg_controls'], 2) }}</td>
                        <td style="text-align: center;">{{ $grade['sum_exams'] }}</td>
                        <td style="text-align: center;">{{ $grade['coefficient'] }}</td>
                        <td style="text-align: center;">
                            @php
                                $finalGrade = $grade['final_grade'];
                                $gradeClass = '';
                                if ($finalGrade >= 15)
                                    $gradeClass = 'grade-excellent';
                                elseif ($finalGrade >= 12)
                                    $gradeClass = 'grade-good';
                                elseif ($finalGrade >= 10)
                                    $gradeClass = 'grade-average';
                                else
                                    $gradeClass = 'grade-poor';
                            @endphp
                            <span class="{{ $gradeClass }}">{{ number_format($finalGrade, 2) }}</span>
                        </td>
                        <td style="font-size: 10px;">{{ $grade['teacher'] }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 20px; color: #9ca3af;">
                            No grades available for this academic year.
                        </td>
                    </tr>
                @endforelse
            </tbody>
        </table>

        <!-- Attendance Section -->
        <div class="attendance-section">
            <div class="section-title">Attendance Summary</div>
            <div class="attendance-stats">
                <div class="stat-item">
                    <div class="stat-label">Total Days</div>
                    <div class="stat-value">{{ $attendanceStats['total'] }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Present</div>
                    <div class="stat-value" style="color: #059669;">{{ $attendanceStats['present'] }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Absent</div>
                    <div class="stat-value" style="color: #dc2626;">{{ $attendanceStats['absent'] }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Late</div>
                    <div class="stat-value" style="color: #d97706;">{{ $attendanceStats['late'] }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Excused</div>
                    <div class="stat-value">{{ $attendanceStats['excused'] }}</div>
                </div>
                <div class="stat-item">
                    <div class="stat-label">Percentage</div>
                    <div class="stat-value" style="color: #2563eb;">{{ $attendanceStats['percentage'] }}%</div>
                </div>
            </div>
        </div>

        <!-- Signature Section -->
        <div class="signature-section">
            <div class="signature-box">
                <div style="font-size: 11px; color: #6b7280; margin-bottom: 40px;">Class Teacher</div>
                <div style="font-size: 10px;">_______________________</div>
                <div style="font-size: 10px; color: #9ca3af; margin-top: 5px;">Signature & Date</div>
            </div>
            <div class="signature-box">
                <div style="font-size: 11px; color: #6b7280; margin-bottom: 40px;">School Director</div>
                <div style="font-size: 10px;">_______________________</div>
                <div style="font-size: 10px; color: #9ca3af; margin-top: 5px;">Signature & Stamp</div>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <div>This is an official document generated by {{ $student->class->school->name }}</div>
            <div style="margin-top: 5px;">Generated on {{ $generatedDate }}</div>
        </div>
    </div>
</body>

</html>