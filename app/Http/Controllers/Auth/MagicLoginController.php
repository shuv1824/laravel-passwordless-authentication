<?php

namespace App\Http\Controllers\Auth;

use Auth;
use Illuminate\Http\Request;

use App\UserLoginToken;

use App\Http\Requests;
use App\Http\Controllers\Controller;
use App\Auth\MagicAuthentication;

class MagicLoginController extends Controller
{   
    protected $redirectOnRequested = "/login/magic";

    // This is the Magic Login page view method
    public function show(){
        return view('auth.magic.login');
    }

    // This is the method that executes when the user gives email address and click login
    public function sendToken(Request $request, MagicAuthentication $auth){
        $this->validateLogin($request);  
        $auth->requestLink();  

        // Now redirect to a pretty page and show some flash message... 
        return redirect()->to($this->redirectOnRequested)->with('success', 'We\'ve sent you a magic link!!!');
    }

    // Validate token method
    public function validateToken(Request $request, UserLoginToken $token){
        $token->delete();

        if($token->isExpired()){
            return redirect('/login/magic')->with('error', 'Your magic link has expired!!!');
        }

        if($token->belongsToEmail($request->email)){
            return redirect('/login/magic')->with('error', 'The magic link is not valid');
        }

        Auth::login($token->user, $request->remember);

        return redirect()->intended();
    }

    public function validateLogin(Request $request){
        $this->validate($request, [
            'email' => 'required|email|max:255|exists:users,email'
        ]);
    }
}
