<?php

namespace App\Http\Services;

use DateTime;
use Illuminate\Database\Eloquent\Collection;
use Streams\Core\Support\Facades\Streams;


//service doesn't have to deal with the request item directly.
class DanceClassService
{
    public function getAllClasses()
    {
        return Streams::entries('danceclasses')->get();
    }

    public function getClassByInstructor($instructor)
    {
        return Streams::entries('danceclasses')
            ->where('instructor', '=', $instructor)
            ->get();
    }

    public function getClassByDate($date)
    {
        $newdate = new DateTime($date);
        return Streams::entries('danceclasses')
            ->where('date_of_class', '=', $newdate->format('Y-m-d'))
            ->first()
            ->id;
    }

    public function create(Collection $data)
    {
        $newdate = new DateTime($data->date);
        $class = Streams::factory('danceclasses')->create([
            'instructor' => $data->instructor,
            'date_of_class' => $newdate->format('Y-m-d'),

            //optional pieces
            'assistant' => $data->assistant ?? '',
            'total_students' => $data->total_students ?? 0,
            'students_from_last_week' => $data->students_from_last_week ?? 0,
            'returning_students' => $data->returning_students ?? 0,
            'new_students' => $data->new_students ?? 0,
        ]);
        $class->save();
        return $class;
    }

    public function update($id, Collection $collection)
    {
        $class = Streams::entries('danceclasses')->find($id);
        $collection->map(function ($value, $key) use ($class) {
            if ($value) {
                $class->$key = $value;
            }
        });
        $class->save();
        return $class;
    }
}

//https://streams.dev/docs/core/repositories lol improve that page. there's nothing there.
// what are the function options from streams?