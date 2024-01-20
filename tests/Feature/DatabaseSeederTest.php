<?php

use App\Models\Course;
use App\Models\User;
use App\Models\Video;
use Illuminate\Support\Facades\App;

it('adds given courses', function () {
    $this->assertDatabaseCount(Course::class, 0);

    $this->artisan('db:seed');

    $this->assertDatabaseCount(Course::class, 3);
    $this->assertDatabaseHas(Course::class, ['title' => 'Laravel For Beginners']);
    $this->assertDatabaseHas(Course::class, ['title' => 'Advanced Laravel']);
    $this->assertDatabaseHas(Course::class, ['title' => 'TDD The Laravel Way']);
});

it('adds given courses only once', function () {
    $this->artisan('db:seed');
    $this->artisan('db:seed');

    $this->assertDatabaseCount(Course::class, 3);
});

it('adds given videos', function () {
    $this->assertDatabaseCount(Video::class, 0);

    $this->artisan('db:seed');

    $courses = Course::all();
    $this->assertDatabaseCount(Video::class, 9);

    expect($courses->each->vidoes)->toHaveCount(3);
});

it('adds given videos only once', function () {
    $this->artisan('db:seed');
    $this->artisan('db:seed');

    $this->assertDatabaseCount(Video::class, 9);
});

it('adds local test user', function () {
    App::partialMock()->shouldReceive('environment')->andReturn('local');
    $this->assertDatabaseCount(User::class, 0);

    $this->artisan('db:seed');

    $this->assertDatabaseCount(User::class, 1);

});

it('does not add test user for production', function () {
    App::partialMock()->shouldReceive('environment')->andReturn('production');
    $this->assertDatabaseCount(User::class, 0);

    $this->artisan('db:seed');

    $this->assertDatabaseCount(User::class, 0);
});
