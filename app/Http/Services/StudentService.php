<?php

namespace App\Http\Services;

use Streams\Core\Support\Facades\Streams;

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
}
