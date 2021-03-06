<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */

     /* cmd */
    //  php artisan db:seed
    public function run()
    {
         \App\Models\Book::factory(10)->create();
         \App\Models\Note::factory(50)->create();
         \App\Models\Task::factory(50)->create();
         \App\Models\TaskHistory::factory(200)->create();



    }
}
