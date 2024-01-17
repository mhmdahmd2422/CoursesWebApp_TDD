<?php

use App\Models\Course;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

use function Pest\Laravel\get;

it('cannot be accessed by guests', function () {
    get(route('pages.dashboard'))
        ->assertRedirectToRoute('login');
});

it('lists purchased courses', function () {
    $user = User::factory()
        ->has(Course::factory()->count(2)->sequence(
            ['title' => 'Course A'],
            ['title' => 'Course B'],
        ))
        ->create();
    $this->actingAs($user);

    get(route('pages.dashboard'))
        ->assertOk()
        ->assertSeeText([
            'Course A',
            'Course B',
        ]);
});

it('does not list any other courses', function () {
    $user = User::factory()->create();
    $course = Course::factory()->create();

    $this->actingAs($user);
    get(route('pages.dashboard'))
        ->assertOk()
        ->assertDontSeeText($course->title);
});

it('shows latest purchased courses first', function () {
    $user = User::factory()->create();
    $firstPurchasedCourse = Course::factory()->create();
    $lastPurchasedCourse = Course::factory()->create();

    $user->courses()->attach($firstPurchasedCourse, ['created_at' => \Carbon\Carbon::yesterday()]);
    $user->courses()->attach($lastPurchasedCourse, ['created_at' => \Carbon\Carbon::now()]);
    $this->actingAs($user);

    get(route('pages.dashboard'))
        ->assertOk()
        ->assertSeeTextInOrder([
            $lastPurchasedCourse->title,
            $firstPurchasedCourse->title,
        ]);
});

it('includes link to product videos', function () {
    $user = User::factory()->has(Course::factory())->create();

    $this->actingAs($user);
    get(route('pages.dashboard'))
        ->assertOk()
        ->assertSeeText('Watch Videos')
        ->assertSee(route('page.course-videos', Course::first()));
});
