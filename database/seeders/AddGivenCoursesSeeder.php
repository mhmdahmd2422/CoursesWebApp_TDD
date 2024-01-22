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

        Course::factory()->released()->count(3)
            ->sequence(
                ['title' => 'Laravel For Beginners', 'paddle_price_id' => 'pri_01hmmnet44vr1bcs6wgh9wmvfq'],
                ['title' => 'Advanced Laravel', 'paddle_price_id' => 'pri_01hmq55wfak6x55hynk1g4a483'],
                ['title' => 'TDD The Laravel Way', 'paddle_price_id' => 'pri_01hmq581ce83ran1ezeswznv4e']
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
