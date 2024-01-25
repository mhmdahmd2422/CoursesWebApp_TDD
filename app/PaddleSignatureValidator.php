<?php

namespace App;

use Illuminate\Http\Request;
use Spatie\WebhookClient\Exceptions\InvalidConfig;
use Spatie\WebhookClient\SignatureValidator\SignatureValidator;
use Spatie\WebhookClient\WebhookConfig;

class PaddleSignatureValidator implements SignatureValidator
{
    public function isValid(Request $request, WebhookConfig $config): bool
    {
        $signature = $request->header($config->signatureHeaderName);
        if (! $signature) {
            return false;
        }
        $secretPaddleKey = $config->signingSecret;
        if (empty($secretPaddleKey)) {
            throw InvalidConfig::signingSecretNotSet();
        }
        $requestBody = $request->getContent();

        $extractedSignature = explode(';', $signature, 2);
        $signedValue = explode('=', $extractedSignature[1]);
        $signedValue = $signedValue[1];

        $timestamp = explode('=', $extractedSignature[0]);
        $timestamp = $timestamp[1];

        $requestBody = $timestamp.':'.$requestBody;

        $computedSignature = hash_hmac('sha256', $requestBody, $secretPaddleKey);
        // Verify the signature
        return hash_equals($computedSignature, $signedValue);
    }
}
