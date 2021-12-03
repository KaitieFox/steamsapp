<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ClassesController extends Controller
{
    public function getClasses()
    {
        return Streams::entries('classes')->all();
    }
}
