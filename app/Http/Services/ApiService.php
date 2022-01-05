<?php

namespace App\Http\Services;

use Illuminate\Support\Facades\Response;

class ApiService
{
    public function handleSubmit($data)
    {
        $classCollection = collect($data->class);
        $students = $data->students;

        // check for unique date
        // if ($this->classService->isDuplicateDate($classCollection->get('date'))) {
        //     return Response::json([
        //         'status' => 'failed',
        //         'message' => 'Not unique date'
        //     ]);
        // }

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

        // not best way to do it.
        if ($allStudents && $lastWeekStudents) {
            // add returning students count to collection
            // $returningStudents = $totalStudents - ($newStudentCount + $lastWeekStudentDiffCount);
            //put all students into an array
            $array = $allStudents->toArray();
            // returningStudents is the count of array intersect with students from this week and students from 
            // all time, minus the ones from last week
            $returningStudents = (count(array_intersect($students, $array))) - $lastWeekStudentDiffCount;
            $classCollection->put('returning_students', $returningStudents);
        }

        // create the class 
        $class = $this->classService->create($classCollection);

        if (!$class) {
            return Response::json([
                'status' => 'failed',
                'message' => 'Not unique date'
            ]);
        }

        // save students with the class id
        $this->studentService->create($class, collect($data->students));

        // return
        return $classCollection;
    }
}
