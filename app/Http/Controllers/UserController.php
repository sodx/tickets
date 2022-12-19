<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Stevebauman\Location\Facades\Location;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (getenv('APP_ENV') === 'local') {
            $ip = '48.188.144.248'; /* Static IP address */
        //$ip = '89.64.5.88'; /* Static IP address */
        } else {
            $ip = $request->ip(); // Dynamic IP address
        }

        $currentUserInfo = Location::get($ip);

        return view('user', compact('currentUserInfo'));
    }
}
