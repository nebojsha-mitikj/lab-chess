<?php

namespace App\Http\Controllers\WebControllers\Subscription;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;

class PricingController extends Controller
{
    public function index(): View {
        return view('subscription.pricing');
    }
}
