<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class Send extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('send');
    }
}
