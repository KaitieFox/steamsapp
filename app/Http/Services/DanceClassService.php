<?php

namespace App\Http\Services;

use DateTime;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
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
            ->first();
    }

    public function create(Collection $data)
    {
        $newdate = new DateTime($data->get('date'));
        $class = Streams::entries('danceclasses')->create([
            'id' => Str::uuid(),
            'instructor' => $data->get('instructor'),
            'date_of_class' => $newdate->format('Y-m-d'),
            'assistant' => $data->get('assistant'),
            'total_students' => $data->get('total_students'),
            'students_from_last_week' => $data->get('students_from_last_week'),
            'returning_students' => $data->get('returning_students'),
            'new_students' => $data->get('new_students'),
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

    public function isDuplicateDate($date): bool
    {
        return $this->getClassByDate($date) ? true : false;
    }

    public function exportCSV()
    {
        $classes = $this->getAllClasses();

        //A JSON string.
        $jsonString = $classes;

        //Decode the JSON and convert it into an associative array.
        $jsonDecoded = json_decode($jsonString, true);

        //Give our CSV file a name.
        $csvFileName = 'example.csv';

        //Open file pointer.
        $fp = fopen($csvFileName, 'w');

        //Loop through the associative array.
        foreach ($jsonDecoded as $row) {
            //Write the row to the CSV file.
            fputcsv($fp, $row);
        }

        //Finally, close the file pointer.
        fclose($fp);
    }
}

//https://streams.dev/docs/core/repositories lol improve that page. there's nothing there.
// what are the function options from streams?
//        "id","created_at","updated_at","__created_at","__updated_at","instructor","date_of_class","assistant","total_students","students_from_last_week","returning_students","new_students"
