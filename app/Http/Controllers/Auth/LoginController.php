<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    /**
     * Determine where to redirect users after login.
     *
     * @return string
     */
    protected function redirectTo()
    {
        $user = Auth::user();

        if ($user->hasRole('superadmin') || $user->hasRole('admin')) {
            return '/dashboard';
        } elseif ($user->hasRole('officer')) {
            return '/dashboard/sales/create';
        } elseif ($user->hasRole('warehouse admin')) {
            return '/dashboard/purchases/create';
        }

        return '/dashboard'; // Default redirect jika tidak ada role yang cocok
    }

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
        $this->middleware('auth')->only('logout');
    }
}
