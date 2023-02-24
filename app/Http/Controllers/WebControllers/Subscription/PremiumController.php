<?php

namespace App\Http\Controllers\WebControllers\Subscription;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Contracts\View\Factory;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\View\View;

class PremiumController extends Controller
{
    /**
     * Gets trial days.
     * @param $subscription
     * @return int
     */
    private function getTrialDays($subscription) : int {
        $trialDays = 14;
        if(Auth::user()->subscriptionStatus() === "canceled"){
            $trialDays = 0;
        }else if(Auth::user()->subscriptionStatus() === "grace" && $subscription->ends_at != null){
            $trialDays = Carbon::now()->diffInDays($subscription->ends_at) + 1;
        }
        return $trialDays;
    }

    /**
     * Return premium page or redirect to subscription page if user is paid already.
     * @return Application|Factory|View|RedirectResponse
     */
    public function index(){
        $subscription = Auth::user()->subscription(env('PADDLE_SUBSCRIPTION'));
        if($subscription != null && Auth::user()->subscriptionStatus() === 'paid'){
            return redirect()->route('subscription');
        }
        return view('subscription.premium')->with([
            'subscriptionStatus' => Auth::user()->subscriptionStatus(),
            'subscriptionEnd' => !empty($subscription->ends_at) ? $subscription->ends_at->format('Y-m-d') : null,
            'payLink' => Auth::user()->newSubscription(
                env('PADDLE_SUBSCRIPTION'),
                env('PADDLE_SUBSCRIPTION_NUMBER'),
            )->trialDays($this->getTrialDays($subscription))->returnTo(route('welcome'))->create()
        ]);
    }
}
