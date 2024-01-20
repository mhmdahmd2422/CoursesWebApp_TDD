<?php

namespace Database\Seeders;

use App\Models\Course;
use Illuminate\Database\Seeder;

class AddGivenCoursesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        if ($this->isDataAlreadyGiven()) {
            return;
        }

        Course::factory()->count(3)
            ->sequence(
                ['title' => 'Laravel For Beginners'],
                ['title' => 'Advanced Laravel'],
                ['title' => 'TDD The Laravel Way']
            )
            ->create();
    }

    private function isDataAlreadyGiven(): bool
    {
        return Course::whereIn('title', [
            'Laravel For Beginners',
            'Advanced Laravel',
            'TDD The Laravel Way',
        ])->exists();
    }
}
