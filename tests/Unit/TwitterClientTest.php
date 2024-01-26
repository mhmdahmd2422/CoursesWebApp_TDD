<?php

use Abraham\TwitterOAuth\TwitterOAuth;
use App\Services\Twitter\TwitterClient;

it('call oauth client for a tweet', function () {
    $mock = Mockery::mock(TwitterOAuth::class)
        ->shouldReceive('post')
        ->withArgs(['tweets', ['text' => 'this is a tweet']])
        ->andReturn(['text' => 'this is a tweet'])
        ->getMock();

    $twitterClient = (new TwitterClient($mock));

    expect($twitterClient->tweet('this is a tweet'))
        ->toEqual(['text' => 'this is a tweet']);
});
