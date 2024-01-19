<?php

use App\Livewire\VideoPlayer;
use App\Models\Course;
use App\Models\Video;
use Livewire\Livewire;

it('shows details for given video', function () {
    $course = Course::factory()
        ->has(Video::factory())->create();
    $video = $course->videos->first();

    Livewire::test(VideoPlayer::class, ['video' => $video])
        ->assertSeeText([
            $video->title,
            $video->description,
            "({$video->duration_in_mins}min)",
        ]);
});

it('shows given video', function () {
    $course = Course::factory()
        ->has(Video::factory())->create();
    $video = $course->videos->first();

    Livewire::test(VideoPlayer::class, ['video' => $video])
        ->assertSeeHtml('<iframe src="https://player.vimeo.com/video/'.$video->vimeo_id.'"');
});

it('shows list of all course videos', function () {
    $course = Course::factory()
        ->has(Video::factory()->count(3)
            ->sequence(
                ['title' => 'First Video'],
                ['title' => 'Second Video'],
                ['title' => 'Third Video'],
            )
        )->create();

    Livewire::test(VideoPlayer::class, ['video' => $course->videos()->first()])
        ->assertSeeText([
            'First Video',
            'Second Video',
            'Third Video',
        ])->assertSeeHtml([
            route('page.course-videos', Video::where('title', 'First Video')->first()),
            route('page.course-videos', Video::where('title', 'Second Video')->first()),
            route('page.course-videos', Video::where('title', 'Third Video')->first()),
        ]);
});
