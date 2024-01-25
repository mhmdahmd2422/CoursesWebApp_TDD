<?php

use Illuminate\Support\Facades\Queue;
use Spatie\WebhookClient\Models\WebhookCall;

use function Pest\Laravel\postJson;

/**
 * @return array
 */
it('stores a paddle purchase request', function () {
    Queue::fake();
    $this->assertDatabaseCount(WebhookCall::class, 0);

    postJson('webhooks', getValidPaddleRequestData(), ['Paddle-Signature' => 'ts=1706136760;h1=b55fdb85fbe6c9524814b59e508cbfe44774a4d6b91c77c88fa5b2726412d762']);

    $this->assertDatabaseCount(WebhookCall::class, 1);
});

it('does not store invalid paddle purchase request', function () {
    $this->assertDatabaseCount(WebhookCall::class, 0);

    postJson('webhooks', getInvalidPaddleRequestData(), ['Paddle-Signature' => 'ts=1706136760;h1=b55fdb85fbe6c9524814b59e508cbfe44774a4d6b91c77c88fa5b2726412d762']);

    $this->assertDatabaseCount(WebhookCall::class, 0);
});

it('dispatches a job for valid paddle request', function () {
    Queue::fake();

    postJson('webhooks', getValidPaddleRequestData(), ['Paddle-Signature' => 'ts=1706136760;h1=b55fdb85fbe6c9524814b59e508cbfe44774a4d6b91c77c88fa5b2726412d762']);

    Queue::assertPushed(\App\Jobs\HandlePaddlePurchaseJob::class);
});

it('does not dispatches a job for invalid paddle request', function () {
    Queue::fake();

    postJson('webhooks', getInvalidPaddleRequestData(), ['Paddle-Signature' => 'ts=1706136760;h1=b55fdb85fbe6c9524814b59e508cbfe44774a4d6b91c77c88fa5b2726412d762']);

    Queue::assertNothingPushed();
});
function getValidPaddleRequestData(): array
{
    return [
        'data' => [
            'id' => 'ctm_01hmytj6p0jmbam2f0da99estn',
            'name' => null,
            'email' => 'holy@mailinator.com',
            'locale' => 'en',
            'status' => 'active',
            'created_at' => '2024-01-24T22:48:29.12Z',
            'updated_at' => '2024-01-24T22:48:29.12Z',
            'custom_data' => null,
            'import_meta' => null,
            'marketing_consent' => false,
        ],
        'event_id' => 'evt_01hmytj7ar9m2ye0jpae901ke5',
        'event_type' => 'customer.created',
        'occurred_at' => '2024-01-24T22:48:29.784575Z',
        'notification_id' => 'ntf_01hmytj7dbfavqbhrr2drjqrke',
    ];
}
function getInvalidPaddleRequestData(): array
{
    return [];
}
