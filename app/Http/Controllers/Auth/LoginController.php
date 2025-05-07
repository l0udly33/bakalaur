<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $credentials = $request->only('email', 'password');

        if (auth()->attempt($credentials)) {

            if (auth()->user()->role === 'blocked') {
                auth()->logout();

                return back()->withErrors([
                    'email' => 'Jūs buvote užblokuotas, kreipkitės į Admin@gmail.com',
                ]);
            }

            return redirect('/')->with('success', 'Prisijungėte prie sistemos');
        }


        return back()->withErrors([
            'email' => 'Neteisingi duomenys',
        ]);
    }
}
