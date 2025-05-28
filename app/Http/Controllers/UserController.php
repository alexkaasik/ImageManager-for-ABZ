<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
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
                'message' => 'The user with the requested id does not exist',
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
        $validator = Validator::make($request->all(), 
        rules:
        [
            'FullName'    => 'required|string|min:2|max:60',
            'E-Mail'      => 'required|email:rfc|unique:user,E-Mail',
            'Phone'       => 'required|phone:AUTO|unique:user,Phone',
            'PositionId'  => 'required|numeric|min:1|max:4',
        ],
        messages:
        [
            'FullName.required' => 'Please provide your full name',
            'FullName.min'      => 'Name must be at least 2 characters',
            'FullName.max'      => 'Name must not exceed 60 characters',
    
            'E-Mail.required' => 'Email is required',
            'E-Mail.email'    => 'Enter a valid email address',
            'E-Mail.unique'   => 'This email is already in use',
    
            'Phone.required' => 'Phone number is required',
            'Phone.phone'   => 'You put wasn\'t a number',
            'Phone.unique'   => 'This phone is already in use',
    
            'PositionId.required' => 'Position ID is required',
            'PositionId.numeric'  => 'Position ID must be a number',
            'PositionId.min'      => 'That positions doesn\'t exist',
            'PositionId.max'      => 'That positions doesn\'t exist',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }
    
        // If validation passes
        $validated = $validator->validated();
        $user = User::create($validated);

        return response()->json([
            'success' => true,
            'Users'    => $user,
            'message' => 'User created successfully',
            
        ], 201);   
    }

    public function handleFormPost(Request $request)
    {
        $response = $this->postUsers($request);
        $data = $response->getData(true); 

        if ($data['success']) {
            return redirect()->route('user.list')->with('success', $data['message']);
        } else {
            return redirect()->back()
                ->withErrors($data['errors'] ?? ['form' => $data['message']])
                ->withInput();
        }
    }

    // Web route

    public function showList()
    {
        $requestUser = Request::create(route('user.get'), 'GET');
        $reponseUser = Route::dispatch($requestUser);
        $users = json_decode($reponseUser->getContent(), true);

        $requestPosition = Request::create(route('position.get'), 'GET');
        $reponsePosition = Route::dispatch($requestPosition);
        $positions  = json_decode($reponsePosition->getContent(), true);
        
        $PositionMap = [];
        foreach ($positions['positions'] as $position) {
            $PositionMap[$position['id']] = $position['name'];
        }

        foreach ($users['users'] as &$user) {
            $user['PositionId'] = $PositionMap [$user['PositionId']];
        }

        $users = $users['users'];
        
        return View('list', compact('users'));
    }

    public function showForm()
    {
        $request = Request::create(route('position.get'), 'GET');
        $reponse = Route::dispatch($request);
        $positions = json_decode($reponse->getContent(), true);

        $positions = $positions['positions'];

        return view('form', compact('positions'));
    }
}