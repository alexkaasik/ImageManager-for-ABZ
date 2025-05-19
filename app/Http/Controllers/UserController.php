<?php

namespace App\Http\Controllers;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function GetUserForm()
    {
         // Send a GET request to the positions API
         $response = Http::get('http://172.25.199.251/positions');
        
         // Decode the response into an array
         $positions = json_decode($response->getBody(), true)['positions'];
 
         // Pass the positions to the view
         return view('CreateUser', compact('positions'));
    }
}
