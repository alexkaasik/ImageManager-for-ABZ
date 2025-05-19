<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Http;

class UserController extends Controller
{
    public function GetUserForm()
    {
        $request = Request::create('/positions', 'GET');
        $reponse = Route::dispatch($request);
        $positions = json_decode($reponse->getContent(), true);

        return view('CreateUser', compact('positions'));
    }
}
