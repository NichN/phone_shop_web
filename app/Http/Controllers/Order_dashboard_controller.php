<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Order_dashboard_controller extends Controller
{
    public function index(){
        return view('Admin.order.index');
    }
}
