<?php

namespace App\Http\Controllers;

use App\Http\Services\DanceClassService;
use App\Http\Services\StudentService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;

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

        // check for unique dates
        // doesn't return right, but it works okay for now.
        if ($this->classService->isDuplicateDate($classCollection->get('date'))) {
            return Response::json([
                'status' => 'failed',
                'message' => 'Not unique date'
            ]);
        }

        // add to total_students to collection based on how many names in the list
        $totalStudents = count($students);
        $classCollection->put('total_students', $totalStudents);

        // todo: make more robust. sometimes there are duplicate names
        // retrieve student names of all time
        $allStudents = $this->studentService->getAllUniqueStudents();
        if ($allStudents) {
            $array = $allStudents->toArray();
            // compare this week's student array with all time. If any remain, then they are new students.
            $newStudentCount = count(array_diff($students, $array));
            // add this to the classCollection
            $classCollection->put('new_students', $newStudentCount);
        } else {
            $classCollection->put('new_students', 0);
        }

        // get last week's student list
        $lastWeekStudents = $this->studentService->getStudentArrayFromLastWeek($classCollection->get('date'));
        if ($lastWeekStudents) {
            $array = $lastWeekStudents->toArray();
            // get the diff. Who was here this week who was not here last week?
            $lastWeekStudentDiffCount = $totalStudents - (count(array_diff($students, $array)));
            // add it to the classCollection
            $classCollection->put('students_from_last_week', $lastWeekStudentDiffCount);
        } else {
            $classCollection->put('students_from_last_week', 0);
        }

        // find students who are returning, but not from last week
        if ($allStudents && $lastWeekStudents) {
            // add returning students count to collection
            // $returningStudents = $totalStudents - ($newStudentCount + $lastWeekStudentDiffCount);
            //put all students into an array
            $array = $allStudents->toArray();
            // returningStudents is the count of array intersect with students from this week and students from 
            // all time, minus the ones from last week
            $returningStudents = (count(array_intersect($students, $array))) - $lastWeekStudentDiffCount;
            $classCollection->put('returning_students', $returningStudents);
        } else {
            $classCollection->put('returning_students', 0);
        }

        // create the class 
        $class = $this->classService->create($classCollection);

        // save students with the class id
        $this->studentService->create($class, collect($request->students));

        // return
        return $classCollection;
    }

    public function exportToCsv()
    {
        $this->classService->exportCSV();
        return "done";
    }
}
