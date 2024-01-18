<?php

use App\Livewire\VideoPlayer;
use App\Models\Course;
use App\Models\Video;

use function Pest\Laravel\get;

it('cannot be accessed by guest', function () {
    $course = Course::factory()->create();

    get(route('page.course-videos', $course))
        ->assertRedirectToRoute('login');
});

it('includes a video player', function () {
    $course = Course::factory()
        ->has(Video::factory())
        ->create();

    loginAsUser();
    get(route('page.course-videos', $course))
        ->assertOk()
        ->assertSeeLivewire(VideoPlayer::class);
});

it('shows first course video by default', function () {
    $course = Course::factory()
        ->has(Video::factory()->state(['title' => 'my video']))
        ->create();

    loginAsUser();
    get(route('page.course-videos', $course))
        ->assertOk()
        ->assertSeeText('my video');
});

it('show provided course video', function () {
    $course = Course::factory()
        ->has(Video::factory()->count(2)
            ->sequence(['title' => 'First Video'], ['title' => 'Second Video'])
        )
        ->create();

    loginAsUser();
    get(route('page.course-videos', ['course' => $course, 'video' => $course->videos()->orderByDesc('id')->first()]))
        ->assertOk()
        ->assertSeeText('Second Video');
});
