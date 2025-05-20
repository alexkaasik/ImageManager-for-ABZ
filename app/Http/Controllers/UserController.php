<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

class UserController extends Controller
{
    public function showList()
    {
        $request = Request::create(route('user.get'), 'GET');
        $reponse = Route::dispatch($request);
        $Users = json_decode($reponse->getContent(), true);

        return View('ListUser', compact('Users'));
    }

    public function showForm()
    {
        $request = Request::create(route('position.getPositions'), 'GET');
        $reponse = Route::dispatch($request);
        $positions = json_decode($reponse->getContent(), true);

        return view('CreateUser', compact('positions'));
    }

    public function getUsersDetail($id) {
        $user = User::find($id);
    
        return response()->json($user);
    }

    public function postUsers(Request $request)
    {
        $validated = $request->validate([
            'FullName' => 'required|string',
            'E-Mail' => 'required|string',
            'Phone' => 'required|string',
            'PositionId' => 'required|numeric',
        ]);

        User::create($validated);

        return redirect() -> route('user.viewUserList');
    }

    public function getUsers()
    {   
        $data = User::all();
        return response()->json(['success'=> true, 'Users' => $data], 200);        
    }
}