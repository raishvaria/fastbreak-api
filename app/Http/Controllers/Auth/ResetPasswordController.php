<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\ResetPasswordRequest;
use App\Models\User;
use Illuminate\Support\Facades\DB;

class ResetPasswordController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(ResetPasswordRequest $request)
    {
        $request->only('email', 'password', 'password_confirmation', 'otp');

        $passwordResets = DB::table('password_resets')
            ->where('otp', $request->otp)
            ->where('email', $request->email)
            ->first();
        
        if(! $passwordResets) {
            return response()->json(['success' => false, 'message' => 'invalid email or otp passed.']);
        }
        
        $passwordResets->delete();
        $user = User::where('email', $request->email)->first();
        $user->password = $request->password;
        $user->save();

        $user->save();

        return response()->json(['success' => true, 'message' => 'Password reset successfully.']);
    }
}
