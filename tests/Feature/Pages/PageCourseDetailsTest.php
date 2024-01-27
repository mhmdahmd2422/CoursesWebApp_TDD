<?php

use App\Models\Course;
use App\Models\Video;

use function Pest\Laravel\get;

it('does not find unreleased course', function () {
    $course = Course::factory()->create();

    get(route('pages.course-details', $course))
        ->assertNotFound();
});

it('shows course details', function () {
    $course = Course::factory()->released()->create();

    get(route('pages.course-details', $course))
        ->assertOk()
        ->assertSeeText([
            $course->title,
            $course->description,
            $course->tagline,
            ...$course->learnings,
        ])
        ->assertSee(asset("images/{$course->image_name}"));
});

it('shows course video count', function () {
    $course = Course::factory()->released()->has(Video::factory()->count(3))->create();
    get(route('pages.course-details', $course))
        ->assertOk()
        ->assertSeeText([
            '3 videos',
        ]);
});

it('includes paddle checkout button', function () {
    config()->set('services.paddle.vendor-id', 'vendor-id');
    $course = Course::factory()->released()->create([
        'paddle_price_id' => 'pri_01hmmnet44vr1bcs6wgh9wmvfq',
    ]);
    get(route('pages.course-details', $course))
        ->assertOk()
        ->assertSee('<script src="https://cdn.paddle.com/paddle/v2/paddle.js"></script>', false)
        ->assertSee("Paddle.Setup({token:'".config('services.paddle.client-token')."'});", false)
        ->assertSee("<a href='#!' data-theme='none' data-items='[".'{"priceId": "'.$course->paddle_price_id.'","quantity": 1}]'."'", false);
});

it('includes title', function () {
    $course = Course::factory()->released()->create();
    $expectedTitle = config('app.name')." - $course->title";

    $response = get(route('pages.course-details', $course))
        ->assertOk();

    $seo = new Juampi92\TestSEO\TestSEO($response->getContent());
    expect($seo->data)
        ->title()->toBe($expectedTitle);
});

it('includes social tags', function () {
    $course = Course::factory()->released()->create();

    $response = get(route('pages.course-details', $course))
        ->assertOk();

    $seo = new Juampi92\TestSEO\TestSEO($response->getContent());
    expect($seo->data)
        ->description()->toBe($course->description)
        ->openGraph()->type->toBe('website')
        ->openGraph()->url->toBe(route('pages.course-details', $course))
        ->openGraph()->title->toBe($course->title)
        ->openGraph()->description->toBe($course->description)
        ->openGraph()->image->toBe(asset("images/$course->image_name"))
        ->twitter()->card->toBe('summary_large_image');
});
