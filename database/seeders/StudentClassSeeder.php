<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Streams\Core\Support\Facades\Streams;

class StudentClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Streams::factory('studentsclasses')->collect(2)->each(function($sc){
            $sc->save();
        });
    }
}
