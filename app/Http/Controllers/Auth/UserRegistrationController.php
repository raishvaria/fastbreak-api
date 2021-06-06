<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\UserRegistrationRequest;
use App\Mail\WelcomeMail;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class UserRegistrationController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UserRegistrationRequest $request)
    {
        $user = User::create($request->getUserPayload());

        $role = config('roles.models.role')::where('name', '=', 'User')->first();
        $user->attachRole($role);

        Mail::to($user->email)->send(new WelcomeMail($user));

        return response()->json([
            'user' => $user
        ]);
    }
}
