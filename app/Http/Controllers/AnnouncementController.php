<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\ClassModel;
use Illuminate\Http\Request;

class AnnouncementController extends Controller
{
    /**
     * Show all announcements visible to current user
     */
    public function index()
    {
        $user = auth()->user();

        // Get school ID based on user role
        $schoolId = $this->getUserSchoolId($user);

        if (!$schoolId) {
            return view('announcements.index', ['announcements' => collect()]);
        }

        // Get published announcements for this school
        $announcements = Announcement::where('school_id', $schoolId)
            ->published()
            ->with(['author', 'targetClass'])
            ->orderBy('published_at', 'desc')
            ->get()
            ->filter(function ($announcement) use ($user) {
                return $announcement->isVisibleTo($user);
            });

        return view('announcements.index', compact('announcements'));
    }

    /**
     * Show single announcement
     */
    public function show($id)
    {
        $announcement = Announcement::with(['author', 'school', 'targetClass'])->findOrFail($id);

        // Verify user can see this
        if (!$announcement->isVisibleTo(auth()->user())) {
            return redirect()->back()->with('error', 'Unauthorized access.');
        }

        return view('announcements.show', compact('announcement'));
    }

    /**
     * Show create form (managers and teachers only)
     */
    public function create()
    {
        $user = auth()->user();

        if (!$user->isManager() && !$user->isTeacher()) {
            return redirect()->back()->with('error', 'Only managers and teachers can create announcements.');
        }

        // Get school ID
        $schoolId = $this->getUserSchoolId($user);

        // Get classes for this school
        $classes = ClassModel::where('school_id', $schoolId)->get();

        return view('announcements.create', compact('classes'));
    }

    /**
     * Store new announcement
     */
    public function store(Request $request)
    {
        $user = auth()->user();

        if (!$user->isManager() && !$user->isTeacher()) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target' => 'required|in:all,specific_class',
            'target_class_id' => 'required_if:target,specific_class|nullable|exists:classes,id',
            'priority' => 'required|in:low,normal,high,urgent',
            'publish_now' => 'boolean',
        ]);

        $schoolId = $this->getUserSchoolId($user);

        $announcement = Announcement::create([
            'school_id' => $schoolId,
            'author_id' => $user->id,
            'title' => $validated['title'],
            'content' => $validated['content'],
            'target' => $validated['target'],
            'target_class_id' => $validated['target_class_id'] ?? null,
            'priority' => $validated['priority'],
            'published_at' => $request->publish_now ? now() : null,
        ]);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement created successfully!');
    }

    /**
     * Show edit form
     */
    public function edit($id)
    {
        $announcement = Announcement::findOrFail($id);
        $user = auth()->user();

        // Only author or manager can edit
        if ($announcement->author_id !== $user->id && !$user->isManager()) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        $schoolId = $this->getUserSchoolId($user);
        $classes = ClassModel::where('school_id', $schoolId)->get();

        return view('announcements.edit', compact('announcement', 'classes'));
    }

    /**
     * Update announcement
     */
    public function update(Request $request, $id)
    {
        $announcement = Announcement::findOrFail($id);
        $user = auth()->user();

        if ($announcement->author_id !== $user->id && !$user->isManager()) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'target' => 'required|in:all,specific_class',
            'target_class_id' => 'required_if:target,specific_class|nullable|exists:classes,id',
            'priority' => 'required|in:low,normal,high,urgent',
        ]);

        $announcement->update($validated);

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement updated successfully!');
    }

    /**
     * Delete announcement
     */
    public function destroy($id)
    {
        $announcement = Announcement::findOrFail($id);
        $user = auth()->user();

        if ($announcement->author_id !== $user->id && !$user->isManager()) {
            return redirect()->back()->with('error', 'Unauthorized.');
        }

        $announcement->delete();

        return redirect()->route('announcements.index')
            ->with('success', 'Announcement deleted successfully!');
    }

    /**
     * Helper to get school ID for current user
     */
    private function getUserSchoolId($user)
    {
        if ($user->isStudent() && $user->student) {
            return $user->student->class->school_id;
        } elseif ($user->isParent() && $user->parentModel) {
            $student = $user->parentModel->students()->first();
            return $student ? $student->class->school_id : null;
        } elseif ($user->isTeacher() && $user->teacher) {
            $school = $user->teacher->schools()->first();
            return $school ? $school->id : null;
        } elseif ($user->isManager() && $user->manager) {
            return $user->manager->school_id;
        }

        return null;
    }
}
