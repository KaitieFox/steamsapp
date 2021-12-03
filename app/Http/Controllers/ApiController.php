<?php

namespace App\Http\Controllers;

use App\Http\Services\DanceClassService;
use App\Http\Services\StudentService;
use Illuminate\Http\Request;

//I want to use this to control all the handles for the dance class and the student saving
class ApiController extends Controller
{
    protected $studentService;
    protected $classService;
    public function __construct(StudentService $studentService, DanceClassService $classService)
    {
        $this->studentService = $studentService;
        $this->classService = $classService;
    }

    public function getUniqueStudentNames(Request $request)
    {
        return $this->studentService->getAllUniqueStudents();
    }
}
