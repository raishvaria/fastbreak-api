<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Auth\Events\Verified;
use Illuminate\Http\Request;

class VerifyEmailController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke($id, $hash)
    {
        $user = User::find($id);

        if (! $user) {
            $this->responseJson(['error' => 'User not found.']);
        }

        $emailVerificationLink = $user->emailVerificationLinks()
            ->latest()
            ->first();

        if ((! $emailVerificationLink) || ! hash_equals((string) $hash, (string) $emailVerificationLink->token)) {
            return view('email.verified', ['message' => 'Invalid url.']);
        }

        if ($emailVerificationLink->expires_at > Carbon::now()) {
            return view('email.verified', ['message' => 'Link expired.']);
        }

        $user->email_verified_at = Carbon::now();
        $user->save();

        return view('email.verified', ['message' => 'Email verified successfully.']);
    }
}
