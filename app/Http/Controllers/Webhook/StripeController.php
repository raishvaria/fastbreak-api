<?php

namespace App\Http\Controllers\Webhook;

use App\Http\Controllers\Controller;
use App\Models\Job;
use Illuminate\Http\Request;

class StripeController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $event = json_decode($request->getContent());

        $object = $event->data->object;
        $message = 'event not found';

        switch ($event->type) {
            case 'payment_intent.succeeded':
                $job = Job::where('stripe_id', $object->id)->first();
                if ($job) {
                    $job->paid = 1;
                    $job->save();
                    $message = 'Job paid successfully.';
                }
                $message = 'Job not found.';
                break;
        }

        return $message;
    }
}
