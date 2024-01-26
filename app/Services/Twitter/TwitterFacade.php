<?php

namespace App\Services\Twitter;

use Illuminate\Support\Facades\Facade;
use Tests\Feature\Fakes\TwitterFake;

class TwitterFacade extends Facade
{
    protected static function getFacadeAccessor(): string
    {
        return TwitterClientInterface::class;
    }

    protected static function fake()
    {
        self::swap(new TwitterFake);
    }
}
