<?php

use App\Jobs\HandlePaddlePurchaseJob;
use App\Mail\NewPurchaseMail;
use App\Models\Course;
use App\Models\PurchasedCourse;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Spatie\WebhookClient\Models\WebhookCall;

beforeEach(function () {
    $this->dummyCustomerWebhookCall = WebhookCall::create([
        'name' => 'default',
        'url' => 'some-url',
        'payload' => [
            'data' => [
                'id' => 'ctm_01hmyr7tz3f2qjbk4pz1x2kbbj',
                'name' => null,
                'email' => 'test@test.com',
                'locale' => 'en',
                'status' => 'active',
                'created_at' => '2024-01-24T22:07:52.291Z',
                'updated_at' => '2024-01-24T22:07:52.291Z',
                'custom_data' => null,
                'import_meta' => null,
                'marketing_consent' => false,
            ],
            'event_id' => 'evt_01hmyr7vchwx2nhpz4rsf7mq39',
            'event_type' => 'customer.created',
            'occurred_at' => '2024-01-24T22:07:52.721998Z',
            'notification_id' => 'ntf_01hmyr7vevt7n04nwg1fedh6na',
        ],
    ]);
    $this->dummyPurchaseWebhookCall = WebhookCall::create([
        'name' => 'default',
        'url' => 'some-url',
        'payload' => [
            'data' => [
                'id' => 'txn_01hmz3k2ma8qkn1h1h7f16s6jq',
                'items' => [
                    [
                        'price' => [
                            'id' => 'pri_01hmq581ce83ran1ezeswznv4e',
                            'type' => 'standard',
                            'status' => 'active',
                            'quantity' => [
                                'maximum' => 1,
                                'minimum' => 1,
                            ],
                            'tax_mode' => 'account_setting',
                            'product_id' => 'pro_01hmq57fdc9tsx4tbkbxrj3483',
                            'unit_price' => [
                                'amount' => '7000',
                                'currency_code' => 'USD',
                            ],
                            'custom_data' => null,
                            'description' => 'TDD The Laravel Way',
                            'trial_period' => null,
                            'billing_cycle' => null,
                            'unit_price_overrides' => [
                            ],
                        ],
                        'price_id' => 'pri_01hmq581ce83ran1ezeswznv4e',
                        'quantity' => 1,
                        'proration' => null,
                    ],
                ],
                'origin' => 'web',
                'status' => 'completed',
                'details' => [
                    'totals' => [
                        'fee' => '400',
                        'tax' => '0',
                        'total' => '7000',
                        'credit' => '0',
                        'balance' => '0',
                        'discount' => '0',
                        'earnings' => '6600',
                        'subtotal' => '7000',
                        'grand_total' => '7000',
                        'currency_code' => 'USD',
                        'credit_to_balance' => '0',
                    ],
                    'line_items' => [
                        [
                            'id' => 'txnitm_01hmz3k7t383651q2h46xth9nd',
                            'totals' => [
                                'tax' => '0',
                                'total' => '7000',
                                'discount' => '0',
                                'subtotal' => '7000',
                            ],
                            'item_id' => null,
                            'product' => [
                                'id' => 'pro_01hmq57fdc9tsx4tbkbxrj3483',
                                'name' => 'TDD The Laravel Way',
                                'type' => 'standard',
                                'status' => 'active',
                                'image_url' => null,
                                'custom_data' => null,
                                'description' => null,
                                'tax_category' => 'standard',
                            ],
                            'price_id' => 'pri_01hmq581ce83ran1ezeswznv4e',
                            'quantity' => 1,
                            'tax_rate' => '0',
                            'unit_totals' => [
                                'tax' => '0',
                                'total' => '7000',
                                'discount' => '0',
                                'subtotal' => '7000',
                            ],
                        ],
                    ],
                    'payout_totals' => [
                        'fee' => '400',
                        'tax' => '0',
                        'total' => '7000',
                        'credit' => '0',
                        'balance' => '0',
                        'discount' => '0',
                        'earnings' => '6600',
                        'fee_rate' => '0.05',
                        'subtotal' => '7000',
                        'grand_total' => '7000',
                        'currency_code' => 'USD',
                        'exchange_rate' => '1',
                        'credit_to_balance' => '0',
                    ],
                    'tax_rates_used' => [
                        [
                            'totals' => [
                                'tax' => '0',
                                'total' => '7000',
                                'discount' => '0',
                                'subtotal' => '7000',
                            ],
                            'tax_rate' => '0',
                        ],
                    ],
                    'adjusted_totals' => [
                        'fee' => '400',
                        'tax' => '0',
                        'total' => '7000',
                        'earnings' => '6600',
                        'subtotal' => '7000',
                        'grand_total' => '7000',
                        'currency_code' => 'USD',
                    ],
                ],
                'checkout' => [
                    'url' => 'https://localhost?_ptxn=txn_01hmz3k2ma8qkn1h1h7f16s6jq',
                ],
                'payments' => [
                    [
                        'amount' => '7000',
                        'status' => 'captured',
                        'created_at' => '2024-01-25T01:26:53.134602Z',
                        'error_code' => null,
                        'captured_at' => '2024-01-25T01:27:03.927393Z',
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
                        'payment_method_id' => 'paymtd_01hmz3m7xzdchxa806z7haqrf4',
                        'payment_attempt_id' => 'cb27173b-dad5-4c4e-a26e-450cc4f964cf',
                        'stored_payment_method_id' => '49cbe9e5-807a-4988-8001-9e2b3f110113',
                    ],
                ],
                'billed_at' => '2024-01-25T01:27:04.694585Z',
                'address_id' => 'add_01hmz3k7nn19xg183pct037x1n',
                'created_at' => '2024-01-25T01:26:14.985765Z',
                'invoice_id' => 'inv_01hmz3qbmqvbvtqsbrps0kem0k',
                'updated_at' => '2024-01-25T01:28:42.971657941Z',
                'business_id' => null,
                'custom_data' => null,
                'customer_id' => 'ctm_01hmz3k7n05a84dzwa6986npfa',
                'discount_id' => null,
                'currency_code' => 'USD',
                'billing_period' => null,
                'invoice_number' => '5730-10011',
                'billing_details' => null,
                'collection_mode' => 'automatic',
                'subscription_id' => null,
            ],
            'event_id' => 'evt_01hmz3qmc4pe9erzhj3swzstxk',
            'event_type' => 'transaction.completed',
            'occurred_at' => '2024-01-25T01:28:44.164385Z',
            'notification_id' => 'ntf_01hmz3qme0de5vrkfv389zand0',
        ],
    ]);
});

it('creates a user with paddle customer id if not exist', function () {
    Mail::fake();
    $this->assertDatabaseCount(User::class, 0);

    (new HandlePaddlePurchaseJob($this->dummyCustomerWebhookCall))->handle();

    $this->assertDatabaseCount(User::class, 1);
    $this->assertDatabaseHas(User::class, [
        'email' => 'test@test.com',
        'name' => 'Test',
    ]);
});

it('it stores a paddle purchase', function () {
    Mail::fake();
    $this->assertDatabaseCount(User::class, 0);
    $this->assertDatabaseCount(PurchasedCourse::class, 0);

    $user = User::factory()->create(['paddle_customer_id' => 'ctm_01hmz3k7n05a84dzwa6986npfa']);
    $course = Course::factory()->create(['paddle_price_id' => 'pri_01hmq581ce83ran1ezeswznv4e']);

    (new HandlePaddlePurchaseJob($this->dummyPurchaseWebhookCall))->handle();

    $this->assertDatabaseHas(PurchasedCourse::class, [
        'user_id' => $user->id,
        'course_id' => $course->id,
    ]);
});

it('sends out purchase email', function () {
    Mail::fake();
    $user = User::factory()->create(['paddle_customer_id' => 'ctm_01hmz3k7n05a84dzwa6986npfa']);
    $course = Course::factory()->create(['paddle_price_id' => 'pri_01hmq581ce83ran1ezeswznv4e']);

    (new HandlePaddlePurchaseJob($this->dummyPurchaseWebhookCall))->handle();

    Mail::assertSent(NewPurchaseMail::class);
});
