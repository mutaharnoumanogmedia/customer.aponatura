<?php

use App\Http\Controllers\Admin\CustomerController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\API\Auth\MagicLinkController;
use App\Http\Controllers\API\Customer\OrderReviewController;
use App\Http\Controllers\API\WaLinkController;
use App\Http\Controllers\CustomerVerificationFlowController;
use App\Http\Controllers\ManyChatSubscriberController;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post("add-notification", [\App\Http\Controllers\NotificationController::class, 'store'])
    ->name("add-notification");


Route::get("get-orders/{skip?}/{take?}", [DashboardController::class, 'getOrdersAPI']);
Route::get("get-customers/{skip?}/{take?}", [DashboardController::class, 'getCustomersAPI']);


Route::prefix("customer")->group(function () {
    Route::post('/magic-link', [MagicLinkController::class, 'send'])
        ->name('api.magic-link.send')->middleware("throttle:20,60");

    Route::post("order-review", [OrderReviewController::class, 'store'])
        ->name("customer.order.review");
});

Route::prefix('v1/wa')
    // ->middleware(['auth:sanctum']) // or your auth of choice
    ->group(function () {
        Route::post('link/start',   [WaLinkController::class, 'start']);   // create session + send OTP
        Route::post('orders/{order_number}', [WaLinkController::class, 'getOrderByOrderNumber']);

        Route::get("many-subscribers", [ManyChatSubscriberController::class, 'index']);
        Route::post("many-subscribers", [ManyChatSubscriberController::class, 'store']);


        Route::post("verify-customer/{verification_type}", [CustomerVerificationFlowController::class, 'verifyCustomerByVerificationTypeAndValue']);
        //verify otp
        Route::post("verify-otp", [CustomerVerificationFlowController::class, 'verifyEmailByOtp']);
    });
