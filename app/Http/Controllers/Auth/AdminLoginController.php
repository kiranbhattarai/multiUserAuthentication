<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Auth\Session;
use App\Http\Controllers\Controller;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class AdminLoginController extends Controller
{
    public function __construct()
    {
        $this->middleware('guest:admin', ['except' => ['logout']]);
    }
    public function showLoginForm()
    {
        return view('auth.admin-login');
    }
    public function login(Request $request)
    {
        //Validate the form data
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);
        //Attempt to log the user in
        $credentials = array(
            'email' => $request->email,
            'password' => $request->password,
        );
        if (Auth::guard('admin')->attempt($credentials, $request->remember)) {
            //if successfull, then redirect to their intended location
            return redirect()->intended(route('admin.dashboard'));
        }
        //if unsuccessfull, then redirect back to the login with the form data
        return $this->sendFailedLoginResponse($request);

    }

    protected function sendFailedLoginResponse(Request $request)
    {
        throw ValidationException::withMessages([
            'email' => [trans('auth.failed')],
        ]);
    }

    public function logout()
    {
        Auth::guard('admin')->logout();

        $request->session()->invalidate();

        return redirect('/');
    }
}
