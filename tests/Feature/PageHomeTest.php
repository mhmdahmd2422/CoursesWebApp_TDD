<?php

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows courses overview', function (){
    $firstCourse = Course::factory()->released()->create();
    $secondCourse = Course::factory()->released()->create();
    $thirdCourse = Course::factory()->released()->create();
   \Pest\Laravel\get(route('home'))
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

    \Pest\Laravel\get(route('home'))
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

    \Pest\Laravel\get(route('home'))
        ->assertSeeInOrder([
            $newerReleasedCourse->title,
            $olderReleasedCourse->title,
        ]);
});
