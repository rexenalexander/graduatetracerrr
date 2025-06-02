<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;

class LoginController extends Controller
{
    public function index(Request $request)
    {
        return view('auth.forgot-password');
    }
    public function changepass(Request $request)
    {
        return view('auth.change-password');
    }

}
