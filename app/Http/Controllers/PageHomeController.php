<?php

namespace App\Http\Controllers;

use App\Models\Course;
use Carbon\Carbon;
use Illuminate\Http\Request;

class PageHomeController extends Controller
{
    public function __invoke()
    {
        $courses = Course::query()
            ->released()
            ->orderByDesc('released_at')
            ->get();
        return view('home', compact('courses'));
    }
}
