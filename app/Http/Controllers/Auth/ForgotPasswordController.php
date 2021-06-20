<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Mail\PasswordResetEmail;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Password;
use Illuminate\Validation\Rule;

class ForgotPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(Request $request)
    {
        $request->validate([
            'email' => [
                'required',
                'email',
                Rule::exists('users', 'email')
            ]
        ]);

        $user = User::where('email', $request->email)->first();
        $notUnique = true;
        $otp = mt_rand(1111, 9999);

        while ($notUnique) {
            $passwordResets = DB::table('password_resets')->where('otp', $otp)->get();
            if (!empty($passwordResets)) {
                $otp = mt_rand(1111, 9999);
            } else {
                $notUnique = false;
            }
        }

        DB::table('password_resets')
            ->insert(['email' => $request->email, 'token' => $otp, 'created_at' => Carbon::new()]);
        $user->notify(new PasswordResetEmail($otp));

        return response()->json(['success' => true]);
    }
}
