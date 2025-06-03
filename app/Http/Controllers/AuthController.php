<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Token;
use Illuminate\Support\Str;

class AuthController extends Controller
{
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
