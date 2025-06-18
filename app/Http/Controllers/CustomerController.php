<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\faq;

class CustomerController extends Controller
{
    public function aboutUs()
    {
        return view('customer.aboutus');
    }

    public function faq()
    {
        $faqs = faq::latest()->get();
        return view('customer.FAQ', compact('faqs'));
    }

    public function contact()
    {
        return view('customer.contact');
    }
}
