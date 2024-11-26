<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogoutController extends Controller
{
    public function __invoke(Request $req)
    {
        Auth::guard("web")->logout();

        $req->session()->invalidate();
        $req->session()->regenerate();

        return response()->noContent();
    }
}
