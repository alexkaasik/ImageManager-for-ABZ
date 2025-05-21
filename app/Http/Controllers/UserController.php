<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class UserController extends Controller
{
    // API route
    public function getUsers(Request $request)
    {   

        $validator = Validator::make($request->all(), 
        rules:
        [
            'page' => 'required|numeric|min:1',
            'count' => 'required|numeric|min:1',
        ],
        messages:
        [
            'page.min' => 'That page is too low',
            'page.required' => 'page number is required',

            'count.min' => 'That count is too low',
            'count.required' => 'count number is required',
        ]);
    
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }       

        #$users = User::all();
        
        $users = User::paginate(perPage: $request['count'], page: $request['page']);

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
            'Phone'       => 'required|string|unique:user,Phone',
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