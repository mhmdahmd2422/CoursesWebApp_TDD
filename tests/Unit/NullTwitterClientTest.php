<?php

use App\Services\Twitter\NullTwitterClient;

it('it returns an empty array for a tweet call', function () {
    expect(new NullTwitterClient())
        ->tweet('Our tweet here')
        ->toBeArray()
        ->toBeEmpty();
});
