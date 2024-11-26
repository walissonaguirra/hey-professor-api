<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{Auth};

class RegisterController extends Controller
{
    public function __invoke(Request $req)
    {
        $user = User::create($req->input());

        Auth::login($user);
    }
}
