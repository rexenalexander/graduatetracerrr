<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Models\User;
use App\Providers\RouteServiceProvider;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    // ORIGINAL AUTHENTICATION
    // public function store(LoginRequest $request): RedirectResponse
    // {
    //     $user = User::where('email', $request->email)->first();

    //     if($user && $user->changepass != 1) {
    //         return redirect()->intended(route('change-pass'));
    //     }

    //     $request->authenticate();

    //     $request->session()->regenerate();

    //     if (auth()->user()->email === 'admin@gmail.com') {
    //         return redirect()->intended(route('admin.dashboard'));
    //     }

    //     return redirect()->intended(RouteServiceProvider::HOME);
    // }

    // CUSTOM AUTHENTICATION

    public function store(LoginRequest $request): RedirectResponse
    {
        $input = $request->email; // could be email OR firstname+lastname

        // Try to find user by email
        $user = User::where('email', $input)->first();
        
        // Force password change?
        // if ($user && $user->changepass != 1) {
        //     return redirect()->intended(route('change-pass'));
        // }

        // If no match by email, try by firstname + lastname
        if (!$user) {
            $user = User::whereRaw("LOWER(CONCAT(firstname, ' ', lastname)) = ?", [strtolower($input)])->first();
        }

        if (!$user) {
            return back()->withErrors(['email' => 'These credentials do not match our records.']);
        }

        // Validate password manually since we're overriding default email check
        if (!\Hash::check($request->password, $user->password)) {
            return back()->withErrors(['password' => 'Incorrect password.']);
        }

        // Login the user
        Auth::login($user);
        $request->session()->regenerate();


        // Redirect logic
        if ($user->email === 'admin@gmail.com') {
            return redirect()->intended(route('admin.dashboard'));
        }

        return redirect()->intended(RouteServiceProvider::HOME);
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
