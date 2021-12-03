<?php

namespace App\Http\Controllers;

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
        return "it works";

    }
}

//https://streams.dev/docs/core/repositories lol improve that page. there's nothing there.
// what are the function options from streams?