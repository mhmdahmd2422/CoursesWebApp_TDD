<?php

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;


it('gives back successful response for home page', function () {
    get(route('pages.home'))
        ->assertOk();
});

it('gives back successful response for course details page', function () {
    $course = Course::factory()->released()->create();
    get(route('pages.course-details', $course))
        ->assertOk();
});

it('gives back successful response for dashboard page', function () {
    $user = User::factory()->create();

    $this->actingAs($user);
    get(route('pages.dashboard'))
        ->assertOk();
});
