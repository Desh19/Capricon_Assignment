<?php

namespace App\Http\Controllers;

use App\Models\Member;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class MemberController extends Controller
{
    public function showRegistrationForm()
    {
        return view('register');
    }

    public function register(Request $request)
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:members',
            'contact_no' => 'required',
            'home_address' => 'required',
            'password' => 'required|min:6|confirmed',
        ]);

        $member = new Member();
        $member->name = $request->input('name');
        $member->email = $request->input('email');
        $member->contact_no = $request->input('contact_no');
        $member->home_address = $request->input('home_address');
        $member->password = bcrypt($request->input('password'));
        $member->save();

        return redirect('/login')->with('success', 'Registration completed successfully.');
    }

    public function showLoginForm()
    {
        return view('login');
    }

    public function login(Request $request)
    {
        $this->validate($request, [
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::attempt($credentials)) {
            return redirect('/dashboard');
        } else {
            return back()->withErrors(['email' => 'Invalid email or password.'])->withInput();
        }
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login')->with('success', 'You have been logged out.');
    }
}
