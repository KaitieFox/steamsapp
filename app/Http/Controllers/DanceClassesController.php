<?php

namespace App\Http\Controllers;

use DateTime;
use Illuminate\Http\Request;
use Streams\Core\Support\Facades\Streams;

class DanceClassesController extends Controller
{
    public function getAllClasses()
    {
        return Streams::entries('danceclasses')->get(); 
    }

    public function getByInstructor(Request $request)
    {
        return Streams::entries('danceclasses')
        ->where('instructor', '=', $request->instructor)
        ->get();   
    }

    public function create(Request $request)
    {
        $date = new DateTime($request->date);
        $class = Streams::factory('danceclasses')->create([
            'instructor' => $request->instructor,
            'date_of_class' => $date->format('Y-m-d'),

            //optional pieces
            'assistant' => $request->assistant ?? 'fox',
            'total_students' => $request->total_students ?? 0,
            'students_from_last_week' => $request->students_from_last_week ?? 0,
            'returning_students' => $request->returning_students ?? 0,
            'new_students' => $request->new_students ?? 0,
        ]);
        $class->save();
        return $class;
    }
}

//https://streams.dev/docs/core/repositories lol improve that page. there's nothing there.
// what are the function options from streams?