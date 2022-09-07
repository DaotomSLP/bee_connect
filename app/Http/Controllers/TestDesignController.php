<?php

namespace App\Http\Controllers;

use App\Models\Branchs;
use App\Models\Districts;
use App\Models\Provinces;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class TestDesignController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function index(Request $request)
    {
        $branchs = Branchs::where('enabled', '1')->get();

        return view('testdesign.home', compact('branchs'));
    }

    public function detail(Request $request)
    {
        $branchs = Branchs::where('enabled', '1')->get();

        return view('testdesign.detail', compact('branchs'));
    }

    public function access_denied(){
        return view('accessDenied');
    }
}
