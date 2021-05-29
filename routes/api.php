<?php

use App\Http\Controllers\Auth\DriverRegistrationController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\LogoutController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\Customer\BootstrapController;
use App\Http\Controllers\Customer\JobsController;
use App\Http\Controllers\Customer\PaymentController;
use App\Http\Controllers\Customer\UpdateProfileController;
use App\Http\Controllers\Webhook\StripeController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/


// Stripe webhook
//----------------------------------

Route::post('/stripe/webhook', StripeController::class);


// Authentication & Password Reset
//----------------------------------

Route::group(['prefix' => 'auth'], function () {

    Route::post('login', LoginController::class);

    Route::post('logout', LogoutController::class);

    Route::post('driver/register', DriverRegistrationController::class);

    Route::post('forgot-password', ForgotPasswordController::class);

    Route::post('reset-password', ResetPasswordController::class);
});

Route::middleware(['auth:sanctum'])->group(function () {

    Route::get('bootstrap', BootstrapController::class);

    Route::post('profile', UpdateProfileController::class);

    Route::post('jobs/{job}/pay', PaymentController::class);

    Route::apiResource('jobs', JobsController::class);
});
