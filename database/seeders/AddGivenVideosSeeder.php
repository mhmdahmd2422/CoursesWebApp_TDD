<?php

namespace Database\Seeders;

use App\Models\Course;
use App\Models\Video;
use Illuminate\Database\Seeder;

class AddGivenVideosSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if ($this->isDataAlreadyGiven()) {
            return;
        }

        $courses = Course::all();

        Video::factory()->count(9)
            ->sequence(
                ['course_id' => $courses[0]->id],
                ['course_id' => $courses[1]->id],
                ['course_id' => $courses[2]->id],
            )->create();
    }

    private function isDataAlreadyGiven(): bool
    {
        $courses = Course::all();

        return $courses[0]->videos()->count() === 3
            && $courses[1]->videos()->count() === 3
            && $courses[2]->videos()->count() === 3;
    }
}
