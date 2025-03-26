<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use http\Url;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('backend.dashboard');
    }

    public function logout(): RedirectResponse
    {
        Auth::forget();
        Auth::logout();

        return redirect()->route('login');
    }
}
