<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request, Job $job)
    {
        $user = Auth::user();

        if ($user->stripe_id === null) {
            $customer = \Stripe\Customer::create();
            $user->stripe_id = $customer->id;
            $user->save();
        }

        $ephemeralKey = \Stripe\EphemeralKey::create(
            ['customer' => $user->stripe_id],
            ['stripe_version' => '2020-08-27']
        );

        $paymentIntent = \Stripe\PaymentIntent::create([
            'amount' => 1099,
            'currency' => 'usd',
            'customer' => $user->stripe_id
        ]);

        $job->stripe_id = $paymentIntent->id;
        $job->save();

        return response()->json([
            'paymentIntent' => $paymentIntent->client_secret,
            'ephemeralKey' => $ephemeralKey->secret,
            'customer' => $user->stripe_id
        ]);
    }
}
