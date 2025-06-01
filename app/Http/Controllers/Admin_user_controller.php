<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Admin_user_controller extends Controller
{
   public function index(){
    return view('Admin.user.Add_form');
   }
}
