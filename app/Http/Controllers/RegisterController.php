<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth};

class RegisterController extends Controller
{
    public function __invoke(Request $req)
    {
        $data = $req->validate([
            'name'     => ['required', 'min:3', 'max:255'],
            'email'    => ['required', 'min:3', 'max:255', 'email', 'unique:users'],
            'password' => ['required', 'min:8', 'max:40'],
        ]);

        $user = User::create($data);

        Auth::login($user);
    }
}
