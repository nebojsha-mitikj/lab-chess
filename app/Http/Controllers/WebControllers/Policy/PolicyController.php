<?php

namespace App\Http\Controllers\WebControllers\Policy;

use App\Http\Controllers\Controller;

class PolicyController extends Controller
{

    public function termsOfService(){
        return view('policy.terms-of-service');
    }

    public function privacyPolicy(){
        return view('policy.privacy');
    }

    public function refundPolicy(){
        return view('policy.refund');
    }

}
