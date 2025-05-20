<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Models\User;

class UserController extends Controller
{
    // API route
    public function getUsers()
    {   
        $users = User::all();

        return response()->json([
            'success'=> true,
            'users' => $users
        ], 200);        
    }

    public function getUsersDetail($id) 
    {
        if (is_int($id))
        {
            return response()->json([
                'success'=> false,
                'message' => 'The user with the requested id does not exist.',
                'fail' => '"The user ID must be an integer."',
            ], 400);
        }
        
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success'=> false,
                'message' => 'User not found'
            ], 404);
        }

        return response()->json([
            'success'=> true,
            'user' => $user
        ], 200);
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

        return redirect() -> route('user.list');
    }

    // Web route

    public function showList()
    {
        $request = Request::create(route('user.get'), 'GET');
        $reponse = Route::dispatch($request);
        $users = json_decode($reponse->getContent(), true);

        return View('list', compact('users'));
    }

    public function showForm()
    {
        $request = Request::create(route('position.get'), 'GET');
        $reponse = Route::dispatch($request);
        $positions = json_decode($reponse->getContent(), true);

        return view('form', compact('positions'));
    }
}