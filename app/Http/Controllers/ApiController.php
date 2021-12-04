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

    public function handleSubmit(Request $request)
    {
        // put request into collection
        $classCollection = collect($request->class);
        $students = $request->students;

        // add to total_students to collection based on how many names in the list
        $totalStudents = count($students);
        $classCollection->put('total_students', $totalStudents);

        // retrieve student names of all time
        $allStudents = $this->studentService->getAllUniqueStudents();
        if ($allStudents) {
            $array = $allStudents->toArray();
            // compare this week's student array with all time. If any remain, then they are new students.
            $newStudentCount = count(array_diff($students, $array));
            // add this to the classCollection
            $classCollection->put('new_students', $newStudentCount);
        }

        // get last week's student list
        $lastWeekStudents = $this->studentService->getStudentArrayFromLastWeek($classCollection->get('date'));
        if ($lastWeekStudents) {
            $array = $lastWeekStudents->toArray();
            // get the diff. Who was here this week who was not here last week?
            $lastWeekStudentDiffCount = $totalStudents - (count(array_diff($students, $array)));
            // add it to the classCollection
            $classCollection->put('students_from_last_week', $lastWeekStudentDiffCount);
        }

        if ($allStudents && $lastWeekStudents) {
            // add returning students count to collection
            $returningStudents = $totalStudents - ($newStudentCount + $lastWeekStudentDiffCount);
            $classCollection->put('returning_students', $returningStudents);
        }

        // create the class 
        $class = $this->classService->create($classCollection);
        // save students with the class id
        $this->studentService->create($class, collect($request->students));

        // return
        return $classCollection;
    }
}
