<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\App;

class AddLocalTestUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if (App::environment() === 'local') {
            $user = User::create([
                'email' => 'test@test.com',
                'name' => 'Mohamed',
                'password' => bcrypt('test'),
            ]);

            $courses = Course::all();
            $user->purchasedCourses()->attach($courses);
        }
    }
}
