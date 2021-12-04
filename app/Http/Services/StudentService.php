<?php

namespace App\Http\Services;

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
