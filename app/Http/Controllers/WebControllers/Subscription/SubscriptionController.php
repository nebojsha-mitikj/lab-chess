<?php

namespace App\Http\Controllers\WebControllers\Subscription;

use App\Http\Controllers\Controller;
use Illuminate\Contracts\View\View;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    /**
     * My Subscription
     * @return View
     */
    public function index(): View {
        return view('subscription.subscription')->with(
            $this->getSubscriptionData()
        );
    }


    /**
     * Get Subscription Data.
     * @return array
     */
    private function getSubscriptionData(): array {
        $data = [
            'subscribed' => Auth::user()->subscribed(env('PADDLE_SUBSCRIPTION')),
            'receipts' => $this->getUserReceipts(),
            'subscription' => [
                'status' => 'Free',
                'next' => '/',
                'amount' => '/'
            ],
            'billing' => null
        ];
        $subscription = Auth::user()->subscription(env('PADDLE_SUBSCRIPTION'));
        if($data['subscribed'] && $subscription != null){
            $data['subscription']['status'] = $subscription->active() ? 'Active' : 'Inactive';
            $data['subscription']['next'] = $subscription->nextPayment() != null ? $subscription->nextPayment()->date : '/';
            $data['subscription']['amount'] = env('AMOUNT');
            if($subscription->active()){
                $data["subscription"]["edit"] = $subscription->updateUrl();
                $data['billing'] = [
                    'email' => $subscription->paddleEmail(),
                    'method' => $subscription->paymentMethod()
                ];
                if($data['billing']['method'] === 'card'){
                    $data['billing']['cardBrand'] = $subscription->cardBrand();
                    $data['billing']['lastFour'] = $subscription->cardLastFour();
                    $data['billing']['expirationData'] = $subscription->cardExpirationDate();
                }
                if($subscription->onGracePeriod() && $subscription->ends_at != null){
                    $data['subscription']['message'] = 'Subscription is canceled.';
                    $data['subscription']['status'] = 'Active until ' . $subscription->ends_at->format("Y-m-d");
                    $data['subscription']['next'] = '/';
                    $data['subscription']['amount'] = '/';
                }
            }
        }
        return $data;
    }

    /**
     * Get User Receipts.
     * @return mixed
     */
    private function getUserReceipts(){
        $receipts = Auth::user()->receipts;
        for($i = 0; $i < count($receipts); $i++)
            $receipts[$i]["paid_at_formatted"] = $receipts[$i]["paid_at"]->format("Y-m-d");
        return $receipts;
    }
}
