<?php

use App\Models\Course;
use App\Models\Video;
use Illuminate\Foundation\Testing\RefreshDatabase;

it('only returns released courses for released scope', function () {
    Course::factory()->released()->create();
    Course::factory()->create();

    expect(Course::released()->get())
        ->toHaveCount(1)
        ->first()->id->toEqual(1);
});

it('has videos', function () {
    $course = Course::factory()->has(Video::factory()->count(2))->create();


    expect($course->videos)
        ->toHaveCount(2)
        ->each->toBeInstanceOf(Video::class);
});
