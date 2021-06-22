<?php

namespace App\Http\Controllers;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return Response
     */
    public function index()
    {
        return view('frontend.home.index');
    }

    public function test(){
        return view('frontend.home.test');
    }
}
