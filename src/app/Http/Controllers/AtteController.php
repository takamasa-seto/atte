<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;

class AtteController extends Controller
{
    public function create()
    {
        /* */
        if(Auth::check())
        {
            return view('stamp');
        }
        else
        {
            return view('auth.login');
        }
        /* */
        /* return view('stamp'); */
    }
}
