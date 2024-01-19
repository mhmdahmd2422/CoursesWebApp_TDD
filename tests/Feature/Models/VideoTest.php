<?php

use App\Models\Course;
use App\Models\Video;

it('belongs to a course', function () {
    $video = Video::factory()
        ->for(Course::factory())
        ->create();

    expect($video->course)
        ->toBeInstanceOf(Course::class);
});

it('gives back readable video duration', function () {
    $video = Video::factory()
        ->create(['duration_in_mins' => 10]);

    expect($video->getReadableDuration())
        ->toEqual('10min');
});
