<?php

namespace App\Http\Controllers\Site;

use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(){
        return view('site.dashboard.index');
    }
}
