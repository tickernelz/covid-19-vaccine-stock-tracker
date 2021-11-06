<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;
use Session;

class AuthController extends Controller
{
    public function formlogin()
    {
        if (Auth::check()) { // true sekalian session field di users nanti bisa dipanggil via Auth
            //Login Success
            return redirect()->route('home');
        }

        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'username' => 'required|string',
            'password' => 'required|string',
        ]);

        $data = [
            'username' => $request->input('username'),
            'password' => $request->input('password'),
        ];
        $remember_me = $request->has('remember');

        Auth::attempt($data, $remember_me);

        if (Auth::check()) { // true sekalian session field di users nanti bisa dipanggil via Auth
            //Login Success
            return redirect()->route('home');
        }

        // false
        Session::flash('error', 'Username atau password salah');

        return redirect()->route('login');
    }

    public function logout()
    {
        Auth::logout(); // menghapus session yang aktif

        return redirect()->route('login');
    }
}
