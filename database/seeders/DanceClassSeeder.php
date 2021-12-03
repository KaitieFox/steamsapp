<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Streams\Core\Support\Facades\Streams;

class DanceClassSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Streams::factory('danceclasses')->collect(2)->each(function($danceclass){
            $danceclass->save();
        });
    }
}
