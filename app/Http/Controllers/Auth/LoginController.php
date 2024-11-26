<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LoginController extends Controller
{
    public function __invoke(Request $req)
    {
        $data = $req->validate([
            'email'    => ['required', 'min:3', 'max:255', 'email'],
            'password' => ['required', 'min:8', 'max:40'],
        ]);

        if (!Auth::attempt($data)) {
            return response(null, 401);
        }

        return response()->noContent();
    }
}
