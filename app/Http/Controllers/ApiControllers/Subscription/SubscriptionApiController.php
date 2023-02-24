<?php

namespace App\Http\Controllers\ApiControllers\Subscription;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionApiController extends Controller
{
    public function cancel(Request $request) : JsonResponse {
        Auth::user()->subscription(env('PADDLE_SUBSCRIPTION'))->cancel();
        return response()->json(['message' => 'Subscription is canceled.'], 200);
    }
}
