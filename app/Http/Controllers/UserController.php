<?php

namespace App\Http\Controllers;
use Illuminate\Http\RedirectResponse;
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
            'PositionId.min'      => 'Pick a positions',
            'PositionId.max'      => 'Pick a positions',
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

    public function showList(Request $request)
    {

        if ($request['page'] == null){ $request['page'] = 1; }

        $parameters = new Request([
            'page' => $request['page'],
            'count' => 6,
        ]);

        $response = $this->getUsers($parameters);
        $users = json_decode($response->getContent(), true);
    
        $requestPosition = Request::create(route('position.get'), 'GET');
        $reponsePosition = Route::dispatch($requestPosition);
        $positions  = json_decode($reponsePosition->getContent(), true);
        
        $PositionMap = [];
        foreach ($positions['positions'] as $position) {
            $PositionMap[$position['id']] = $position['name'];
        }

        foreach ($users['users']['data'] as &$user) {
            $user['PositionId'] = $PositionMap [$user['PositionId']];
        }
        
        return view('list', ['users'=> $users['users']['data'], 'links' => $users['users']['links'],]);
    }

    public function showForm()
    {
        $request = Request::create(route('position.get'), 'GET');
        $reponse = Route::dispatch($request);
        $positions = json_decode($reponse->getContent(), true);

        return view('form', ['positions'=> $positions['positions']]);
    }
}