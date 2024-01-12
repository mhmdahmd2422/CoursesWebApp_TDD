<?php

use App\Models\Course;
use Illuminate\Foundation\Testing\RefreshDatabase;

uses(RefreshDatabase::class);

it('shows courses overview', function (){
    Course::factory()->create(['title' => 'Course A', 'description' => 'Description for Course A', 'released_at' => \Carbon\Carbon::now()]);
    Course::factory()->create(['title' => 'Course B', 'description' => 'Description for Course B', 'released_at' => \Carbon\Carbon::now()]);
    Course::factory()->create(['title' => 'Course C', 'description' => 'Description for Course C', 'released_at' => \Carbon\Carbon::now()]);
   \Pest\Laravel\get(route('home'))
       ->assertSeeText([
       'Course A',
       'Description for Course A',
       'Course B',
       'Description for Course B',
       'Course C',
       'Description for Course C',
   ]);
});

it('show only released courses', function () {
    Course::factory()->create(['title' => 'Course A', 'released_at' => \Carbon\Carbon::yesterday()]);
    Course::factory()->create(['title' => 'Course B']);

    \Pest\Laravel\get(route('home'))
        ->assertSeeText([
        'Course A',
        ])
        ->assertDontSeeText([
            'Course B'
        ]);
});

it('shows courses by release date', function () {
    Course::factory()->create(['title' => 'Course A', 'released_at' => \Carbon\Carbon::yesterday()]);
    Course::factory()->create(['title' => 'Course B', 'released_at' => \Carbon\Carbon::today()]);

    \Pest\Laravel\get(route('home'))
        ->assertSeeInOrder([
            'Course B',
            'Course A',
        ]);
});
