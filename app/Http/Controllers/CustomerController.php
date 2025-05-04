<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class CustomerController extends Controller
{
    public function aboutUs()
    {
        return view('customer.aboutus');
    }

    public function faq()
    {
        return view('customer.FAQ');
    }

    public function contact()
    {
        return view('customer.contact');
    }
}
