<?php

use App\Console\Commands\TweetAboutCourseReleaseCommand;
use App\Models\Course;
use Twitter;

it('tweets about release for a provided course', function () {
    Twitter::fake();
    $course = Course::factory()->create();

    $this->artisan(TweetAboutCourseReleaseCommand::class, ['courseId' => $course->id]);

    Twitter::assertTweetSent("I just released $course->title! Check it out on ".route('pages.course-details', $course));
});
