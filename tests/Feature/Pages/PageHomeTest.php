<?php

use App\Models\Course;

use function Pest\Laravel\get;

it('shows courses overview', function () {
    $firstCourse = Course::factory()->released()->create();
    $secondCourse = Course::factory()->released()->create();
    $thirdCourse = Course::factory()->released()->create();
    get(route('pages.home'))
        ->assertSeeText([
            $firstCourse->title,
            $firstCourse->description,
            $secondCourse->title,
            $secondCourse->description,
            $thirdCourse->title,
            $thirdCourse->description,
        ]);
});

it('show only released courses', function () {
    $releasedCourse = Course::factory()->released()->create();
    $notReleasedCourse = Course::factory()->create();

    get(route('pages.home'))
        ->assertSeeText([
            $releasedCourse->title,
        ])
        ->assertDontSeeText([
            $notReleasedCourse->title,
        ]);
});

it('shows courses by release date', function () {
    $olderReleasedCourse = Course::factory()->released(\Carbon\Carbon::yesterday())->create();
    $newerReleasedCourse = Course::factory()->released()->create();

    get(route('pages.home'))
        ->assertSeeInOrder([
            $newerReleasedCourse->title,
            $olderReleasedCourse->title,
        ]);
});

it('includes login if not logged in', function () {
    get(route('pages.home'))
        ->assertOk()
        ->assertSeeText('Login')
        ->assertSee(route('login'));
});

it('includes link to dashboard if logged in', function () {
    loginAsUser();

    get(route('pages.home'))
        ->assertOk()
        ->assertSeeText('Dashboard')
        ->assertSee(route('pages.dashboard'));
});

it('includes courses links', function () {
    $courses = Course::factory()->released()->count(3)->create();

    get(route('pages.home'))
        ->assertOk()
        ->assertSee([
            route('pages.course-details', $courses[0]),
            route('pages.course-details', $courses[1]),
            route('pages.course-details', $courses[2]),
        ]);
});

it('includes title', function () {
    $expectedTitle = config('app.name').' - Home';

    $response = get(route('pages.home'))
        ->assertOk();

    $seo = new Juampi92\TestSEO\TestSEO($response->getContent());
    expect($seo->data)
        ->title()->toBe($expectedTitle);
});

it('includes social tags', function () {
    $response = get(route('pages.home'))
        ->assertOk();

    $seo = new Juampi92\TestSEO\TestSEO($response->getContent());
    expect($seo->data)
        ->description()->toBe(config('app.name').' is the leading learning platform for Laravel developers.')
        ->openGraph()->type->toBe('website')
        ->openGraph()->url->toBe(route('pages.home'))
        ->openGraph()->title->toBe(config('app.name'))
        ->openGraph()->description->toBe(config('app.name').' is the leading learning platform for Laravel developers.')
        ->openGraph()->image->toBe(asset('images/social.png'))
        ->twitter()->card->toBe('summary_large_image');

});
