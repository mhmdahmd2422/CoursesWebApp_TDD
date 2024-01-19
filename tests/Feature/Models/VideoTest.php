<?php

use App\Models\Course;
use App\Models\User;
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

it('tells if current user have already watched a given video', function () {
    $user = User::factory()
        ->has(Video::factory(), 'watchedVideos')
        ->create();

    loginAsUser($user);
    expect($user->watchedVideos()->first()->alreadyWatchedByCurrentUser())
        ->tobeTrue();
});

it('tells if current user have not watched a given video', function () {
    $video = Video::factory()->create();

    loginAsUser();
    expect($video->alreadyWatchedByCurrentUser())
        ->tobeFalse();
});
