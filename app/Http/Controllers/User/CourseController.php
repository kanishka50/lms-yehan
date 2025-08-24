<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class CourseController extends Controller
{
    /**
     * Display a listing of the user's purchased courses.
     */
    public function index()
    {
        $user = Auth::user();
        
        // Get only purchased courses (no subscription logic)
        $courses = User::find(Auth::id())->courses()->orderBy('user_courses.purchased_at', 'desc')->get();
        
        return view('user.courses.index', compact('courses'));
    }

    /**
     * Display the specified course details.
     */
    public function show(Course $course)
    {
        $user = Auth::user();
        
        // Check if user has purchased this course
        if (!User::find(Auth::id())->hasAccessToCourse($course)) {
            return redirect()->route('user.courses.index')
                ->with('error', 'You do not have access to this course. Please purchase it first.');
        }
        
        // Get videos ordered by sequence
        $videos = $course->videos()->orderBy('order_number')->get();
        
        // Get progress for each video
        foreach ($videos as $video) {
            $video->progress = $video->userProgress(Auth::id())->first();
        }
        
        return view('user.courses.show', compact('course', 'videos'));
    }
}