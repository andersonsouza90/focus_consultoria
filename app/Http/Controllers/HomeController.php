<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Session;

class HomeController extends Controller
{
    public function home(){

        if(Session('userAuth')){
            return view('home');
        }

        return redirect("/");

    }
}
