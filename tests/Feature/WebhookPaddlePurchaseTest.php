<?php

use Illuminate\Support\Facades\Queue;
use Spatie\WebhookClient\Models\WebhookCall;

use function Pest\Laravel\post;

/**
 * @return array
 */

it('stores a paddle purchase request', function () {
    Queue::fake();
    $this->assertDatabaseCount(WebhookCall::class, 0);

    post('webhooks', getValidPaddleRequestData(), ['Paddle-Signature' => 'ts=1706041617;h1=dc9c309e752c885527fa30d53c4b083f6470b53']);

    $this->assertDatabaseCount(WebhookCall::class, 1);
});

it('does not store invalid paddle purchase request', function () {
    $this->assertDatabaseCount(WebhookCall::class, 0);

    post('webhooks', getInvalidPaddleRequestData(), ['Paddle-Signature' => 'ts=1706041617;h1=dc9c309e752c885527fa30d53c4b083f6470b53']);

    $this->assertDatabaseCount(WebhookCall::class, 0);
});

it('dispatches a job for valid paddle request', function () {
    Queue::fake();

    post('webhooks', getValidPaddleRequestData(), ['Paddle-Signature' => 'ts=1706041617;h1=dc9c309e752c885527fa30d53c4b083f6470b53']);

    Queue::assertPushed(\App\Jobs\HandlePaddlePurchaseJob::class);
});

it('does not dispatches a job for invalid paddle request', function () {
    Queue::fake();

    post('webhooks', getInvalidPaddleRequestData(), ['Paddle-Signature' => 'ts=1706041617;h1=dc9c309e752c885527fa30d53c4b083f6470b53']);

    Queue::assertNothingPushed();
});
function getValidPaddleRequestData(): array
{
    return [
        'data' => [
            'id' => 'txn_01hmvzw7bqhzjnhzrbxzbd34ps',
            'items' => [
                [
                    'price' => [
                        'id' => 'pri_01hmq55wfak6x55hynk1g4a483',
                        'type' => 'standard',
                        'status' => 'active',
                        'quantity' => ['maximum' => 1, 'minimum' => 1],
                        'tax_mode' => 'account_setting',
                        'product_id' => 'pro_01hmq54z4dnz2s1qvvx6e8htb1',
                        'unit_price' => [
                            'amount' => '6000',
                            'currency_code' => 'USD',
                        ],
                        'custom_data' => null,
                        'description' => 'Advanced Laravel',
                        'trial_period' => null,
                        'billing_cycle' => null,
                        'unit_price_overrides' => [],
                    ],
                    'price_id' => 'pri_01hmq55wfak6x55hynk1g4a483',
                    'quantity' => 1,
                    'proration' => null,
                ],
            ],
            'origin' => 'web',
            'status' => 'paid',
            'details' => [
                'totals' => [
                    'fee' => null,
                    'tax' => '0',
                    'total' => '6000',
                    'credit' => '0',
                    'balance' => '0',
                    'discount' => '0',
                    'earnings' => null,
                    'subtotal' => '6000',
                    'grand_total' => '6000',
                    'currency_code' => 'USD',
                    'credit_to_balance' => '0',
                ],
                'line_items' => [
                    [
                        'id' => 'txnitm_01hmvzwj663trqv3nf050zqde1',
                        'totals' => [
                            'tax' => '0',
                            'total' => '6000',
                            'discount' => '0',
                            'subtotal' => '6000',
                        ],
                        'item_id' => null,
                        'product' => [
                            'id' => 'pro_01hmq54z4dnz2s1qvvx6e8htb1',
                            'name' => 'Advanced Laravel',
                            'type' => 'standard',
                            'status' => 'active',
                            'image_url' => null,
                            'custom_data' => null,
                            'description' => null,
                            'tax_category' => 'standard',
                        ],
                        'price_id' => 'pri_01hmq55wfak6x55hynk1g4a483',
                        'quantity' => 1,
                        'tax_rate' => '0',
                        'unit_totals' => [
                            'tax' => '0',
                            'total' => '6000',
                            'discount' => '0',
                            'subtotal' => '6000',
                        ],
                    ],
                ],
                'payout_totals' => null,
                'tax_rates_used' => [
                    [
                        'totals' => [
                            'tax' => '0',
                            'total' => '6000',
                            'discount' => '0',
                            'subtotal' => '6000',
                        ],
                        'tax_rate' => '0',
                    ],
                ],
                'adjusted_totals' => [
                    'fee' => '0',
                    'tax' => '0',
                    'total' => '6000',
                    'earnings' => '0',
                    'subtotal' => '6000',
                    'grand_total' => '6000',
                    'currency_code' => 'USD',
                ],
            ],
            'checkout' => [
                'url' => 'https://localhost?_ptxn=txn_01hmvzw7bqhzjnhzrbxzbd34ps',
            ],
            'payments' => [
                [
                    'amount' => '6000',
                    'status' => 'captured',
                    'created_at' => '2024-01-23T20:26:41.31824Z',
                    'error_code' => null,
                    'captured_at' => '2024-01-23T20:26:56.587903Z',
                    'method_details' => [
                        'card' => [
                            'type' => 'visa',
                            'last4' => '5556',
                            'expiry_year' => 2025,
                            'expiry_month' => 2,
                            'cardholder_name' => 'Cyrus Woodward',
                        ],
                        'type' => 'card',
                    ],
                    'payment_method_id' => 'paymtd_01hmw01v7nwv9k61jt1pdd5z78',
                    'payment_attempt_id' => '4aa9a6e9-6cad-4761-8547-3b1a147f23dd',
                    'stored_payment_method_id' => 'ec84338b-d011-4fca-bcde-45a7b8dfb1b2',
                ],
            ],
            'billed_at' => '2024-01-23T20:26:56.705142645Z',
            'address_id' => 'add_01hmvzwj1266g5xqed8ed4rr8f',
            'created_at' => '2024-01-23T20:23:37.147002Z',
            'invoice_id' => null,
            'updated_at' => '2024-01-23T20:26:56.705144238Z',
            'business_id' => null,
            'custom_data' => null,
            'customer_id' => 'ctm_01hmvzwj0fn18p83qby3d288cb',
            'discount_id' => null,
            'currency_code' => 'USD',
            'billing_period' => null,
            'invoice_number' => null,
            'billing_details' => null,
            'collection_mode' => 'automatic',
            'subscription_id' => null,
        ],
        'event_id' => 'evt_01hmw02aspyb6hc17wvb05tdc5',
        'event_type' => 'transaction.paid',
        'occurred_at' => '2024-01-23T20:26:57.207123Z',
        'notification_id' => 'ntf_01hmw02avxadypys2nzwzppnjq',
    ];
}
function getInvalidPaddleRequestData(): array
{
    return [];
}
