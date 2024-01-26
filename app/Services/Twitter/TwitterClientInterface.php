<?php

namespace App\Services\Twitter;

interface TwitterClientInterface
{
    public function tweet(string $text): array;
}
