<?php

namespace App\Http\Controllers\Customer;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Http\Requests\UpdateProfileRequest;

class UpdateProfileController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function __invoke(UpdateProfileRequest $request)
    {
        $user = Auth::user();

        $user->update($request->getUserPayload());

        return response()->json([
            'user' => $user
        ]);
    }
}
