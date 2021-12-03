<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Streams\Core\Support\Facades\Streams;
use Faker;

class StudentSeeder extends Seeder
{
    protected $faker;
    public function __construct()
    {
        $this->faker = Faker\Factory::create();
    }
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {     
        Streams::factory('students')->collect(2)->each(function($student){
            $student->name = $this->faker->name;
            $student->save();
        });
    }
}
