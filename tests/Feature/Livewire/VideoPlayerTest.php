<?php

use App\Livewire\VideoPlayer;
use App\Models\Course;
use App\Models\Video;
use Livewire\Livewire;

function createCourseWithVideos(int $videosCount = 1): Course
{
    return $course = Course::factory()
        ->has(Video::factory()->count($videosCount))->create();
}

beforeEach(function () {
    $this->loggedInUser = loginAsUser();
});

it('shows details for given video', function () {
    $course = createCourseWithVideos();
    $video = $course->videos->first();

    Livewire::test(VideoPlayer::class, ['video' => $video])
        ->assertSeeText([
            $video->title,
            $video->description,
            "({$video->duration_in_mins}min)",
        ]);
});

it('shows given video', function () {
    $course = createCourseWithVideos();
    $video = $course->videos->first();

    Livewire::test(VideoPlayer::class, ['video' => $video])
        ->assertSeeHtml('<iframe class="w-full aspect-video rounded mb-4 md:mb-4" src="https://player.vimeo.com/video/'.$video->vimeo_id.'"');
});

it('shows list of all course videos', function () {
    $course = createCourseWithVideos(videosCount: 3);

    Livewire::test(VideoPlayer::class, ['video' => $course->videos()->first()])
        ->assertSeeText([
            ...$course->videos->pluck('title')->toArray(),
        ])->assertSeeHtml([
            route('page.course-videos', [$course, $course->videos[1]]),
            route('page.course-videos', [$course, $course->videos[2]]),
        ]);
});

it('does not include route for current video', function () {
    $course = createCourseWithVideos();

    Livewire::test(VideoPlayer::class, ['video' => $course->videos()->first()])
        ->assertDontSeeHtml(
            route('page.course-videos', $course->videos()->first()),
        );
});

it('marks a video as completed', function () {
    $course = createCourseWithVideos();

    $this->loggedInUser->purchasedCourses()->attach($course);
    expect($this->loggedInUser->watchedVideos)->toHaveCount(0);

    $firstVideo = $course->videos()->first();
    Livewire::test(VideoPlayer::class, ['video' => $firstVideo])
        ->assertMethodWired('markVideoAsCompleted')
        ->call('markVideoAsCompleted')
        ->assertMethodNotWired('markVideoAsCompleted')
        ->assertMethodWired('markVideoAsNotCompleted')
        ->assertSee($firstVideo->title.' <!--[if BLOCK]><![endif]-->✅', false);

    $this->loggedInUser->refresh();
    expect($this->loggedInUser->watchedVideos)
        ->toHaveCount(1)
        ->first()->title->toEqual($course->videos()->first()->title);
});

it('marks a video as not completed', function () {
    $course = createCourseWithVideos();

    $this->loggedInUser->purchasedCourses()->attach($course);
    $this->loggedInUser->watchedVideos()->attach($course->videos()->first());
    expect($this->loggedInUser->watchedVideos)->toHaveCount(1);

    Livewire::test(VideoPlayer::class, ['video' => $course->videos()->first()])
        ->assertMethodWired('markVideoAsNotCompleted')
        ->call('markVideoAsNotCompleted')
        ->assertMethodNotWired('markVideoAsNotCompleted')
        ->assertMethodWired('markVideoAsCompleted');

    $this->loggedInUser->refresh();
    expect($this->loggedInUser->watchedVideos)
        ->toHaveCount(0);
});
