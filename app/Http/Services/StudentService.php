<?php

namespace App\Http\Services;

use Carbon\Carbon;
use DateTime;
use Illuminate\Support\Collection;
use Streams\Core\Support\Facades\Streams;
use Illuminate\Support\Str;

class StudentService
{
    public function getAllStudents()
    {
        return Streams::entries('students')->get();
    }

    public function getAllUniqueStudents()
    {
        return $this->getAllStudents()->pluck('name')->unique();
    }

    public function getStudentArrayFromLastWeek($date)
    {
        $danceClassService = resolve(DanceClassService::class);
        $carbon = new Carbon($date);
        $lastWeek = $danceClassService->getClassByDate($carbon->subDays(7));
        if ($lastWeek) {
            return Streams::entries('students')->where('class', '=', $lastWeek->id)->get()->pluck('name');
        }
        return;
    }

    public function create($class, Collection $students)
    {
        $students->map(function ($student) use ($class) {
            $newEntry = Streams::factory('students')->create([
                'name' => $student,
                'class' => $class->id
            ]);
            $newEntry->save();
        });
    }
}
