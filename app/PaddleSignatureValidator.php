<?php

namespace App;

use Illuminate\Http\Request;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Spatie\WebhookClient\WebhookConfig;

class PaddleSignatureValidator implements SignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        //signature validation logic here
        if ($request->header('Paddle-Signature') && $request->all()) {
            return true;
        }

        return false;
    }
}
