<?php

use App\Models\Course;
use function Pest\Laravel\get;

it('shows courses overview', function () {
    $firstCourse = Course::factory()->released()->create();
    $secondCourse = Course::factory()->released()->create();
    $thirdCourse = Course::factory()->released()->create();
    get(route('pages.home'))
        ->assertSeeText([
            $firstCourse->title,
            $firstCourse->description,
            $secondCourse->title,
            $secondCourse->description,
            $thirdCourse->title,
            $thirdCourse->description,
        ]);
});

it('show only released courses', function () {
    $releasedCourse = Course::factory()->released()->create();
    $notReleasedCourse = Course::factory()->create();

    get(route('pages.home'))
        ->assertSeeText([
            $releasedCourse->title,
        ])
        ->assertDontSeeText([
            $notReleasedCourse->title,
        ]);
});

it('shows courses by release date', function () {
    $olderReleasedCourse = Course::factory()->released(\Carbon\Carbon::yesterday())->create();
    $newerReleasedCourse = Course::factory()->released()->create();

    get(route('pages.home'))
        ->assertSeeInOrder([
            $newerReleasedCourse->title,
            $olderReleasedCourse->title,
        ]);
});

it('includes login if not logged in', function () {
    get(route('pages.home'))
        ->assertOk()
        ->assertSeeText('Login')
        ->assertSee(route('login'));
});

it('includes logout if logged in', function () {
    loginAsUser();

    get(route('pages.home'))
        ->assertOk()
        ->assertSeeText('Logout')
        ->assertSee(route('logout'));
});
