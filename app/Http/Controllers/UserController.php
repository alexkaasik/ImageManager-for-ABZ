<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

class UserController extends Controller
{
    public function viewUserForm()
    {
        $request = Request::create(route('position.getPositions'), 'GET');
        $reponse = Route::dispatch($request);
        $positions = json_decode($reponse->getContent(), true);

        return view('CreateUser', compact('positions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'FullName' => 'required|string',
            'E-Mail' => 'required|string',
            'Phone' => 'required|string',
            'PositionId' => 'required|numeric',
        ]);

        User::create($validated);

        return redirect() -> route('user.viewUserForm');
    }
}