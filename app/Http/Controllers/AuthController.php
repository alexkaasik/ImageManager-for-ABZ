<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Token;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    /*
     * Generating user creation tokens.
     * Stores is it as 255 length randmized characters 
     * Setting up expiring after 40 minutes of creation of the token
     */
    public function generate()
    {
        $token = Str::Random(255);

        Token::create([
            'Token' => $token,
            'Expires_At' => now()->addMinutes(40),
            'Used' => false,
        ]);

        return response()->json([
            'success' => 'true',
            'token' => $token
        ]);
    }
}
