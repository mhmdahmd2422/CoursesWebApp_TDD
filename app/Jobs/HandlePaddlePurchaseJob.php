<?php

namespace App\Jobs;

use App\Mail\NewPurchaseMail;
use App\Models\Course;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Spatie\WebhookClient\Jobs\ProcessWebhookJob;

class HandlePaddlePurchaseJob extends ProcessWebhookJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $payload = $this->webhookCall->payload;
        if ($payload['event_type'] === 'customer.created') {
            $user = User::where('email', $payload['data']['email'])->first();

            if (! $user) {
                $name = explode('@', $payload['data']['email']);
                User::create([
                    'email' => $payload['data']['email'],
                    'paddle_customer_id' => $payload['data']['id'],
                    'name' => ucfirst($name[0]),
                    'password' => bcrypt(Str::uuid()),
                ]);
            }
        }
        if ($payload['event_type'] === 'transaction.completed') {
            $user = User::where('paddle_customer_id', $payload['data']['customer_id'])->first();
            $course = Course::where('paddle_price_id', $payload['data']['items'][0]['price_id'])->first();
            $user->purchasedCourses()->attach($course);
            Mail::to($user->email)->send(new NewPurchaseMail($course));
        }
    }
}
