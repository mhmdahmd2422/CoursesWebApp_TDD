<?php

use App\Models\Course;
use App\Models\User;

it('has courses', function () {
    $user = User::factory()
        ->has(Course::factory()->count(2))
        ->create();

    expect($user->courses)
        ->toHaveCount(2)
        ->each->toBeInstanceOf(Course::class);
});
