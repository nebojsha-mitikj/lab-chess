<?php

namespace App\Http\Controllers\WebControllers\Contact;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ContactController extends Controller
{
    /**
     * Go to Contact page.
     * @return View
     */
    public function index(): View {
        return view('contact.contact');
    }
}
