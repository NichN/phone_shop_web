<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class dashboardcontroller extends Controller
{
    public function index()
    {
        return view('Admin.component.sidebar');
    }
    public function show()
    {
        return view('Admin.dasboard.index');
    }
}
?>
