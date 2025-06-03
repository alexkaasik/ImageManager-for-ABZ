<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Validator;
use Intervention\Image\Laravel\Facades\Image;
use App\Models\User;
use App\Models\Token;
use Tinify\Tinify;

class UserController extends Controller
{
    // API route
    
    /*
    * Functions to retrieve a list of users.
    * 
    * Why use Validator::make() instead of $request->validate()?  
    * It allows adding custom error messages and attribute names.
    * 
    * The function requires two parameters: count and page.
    * - count: Number of entries to display per page.
    * - page: retrieve page numbers.
    *   Used together with count to determine the range of records fetched
    */
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
        $users->setPath(route('user.list'));

        return response()->json([
            'success'=> true,
            'users' => $users
        ], 200);
        
    }
    /*
     * Getting specific user to retrieve
     */
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

    /*
     * Validating incoming requests with custom messages
     * And stores users with incoming request data
     */
    public function postUsers(Request $request)
    {
        $validator = Validator::make($request->all(), 
        rules:
        [
            'FullName'    => 'required|string|min:2|max:60',
            'E-Mail'      => 'required|email:rfc|unique:user,E-Mail',
            'Phone'       => 'required|phone:AUTO|unique:user,Phone',
            'PositionId'  => 'required|numeric|min:1|max:4',
            'Photo'       => 'required|image|max:5120',
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

            'Photo.required' => 'Photo is required',
            'Photo.image' => 'Wasn\'t a imaged posted',
            'Photo.max' => 'You exceeded the file size',
        ]);
        
        // ---- Start Token Validations ----
        $token = Token::where('Token', '=', $request['Token'])->first();

        if ( !$token || $token['Used'] || $token['Expires_At'] < now() ) {
            return response()->json([
                'success' => false,
                'errors' => 'Your sessions has expired'
            ],401);
            $token->update(['used' => true]);
        }
        // ---- End Token Validations ----

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Validation failed',
                'errors'  => $validator->errors(),
            ], 422);
        }
        // It's encoding image, size(70,70) and format to jpg and saving in filesystem.
        $image = $request->file('Photo');   
        $img = Image::read($image->path());
        $resized=$img->cover(70, 70, 'center');
        $jpgEncodedImage = $resized->encodeByMediaType('image/jpeg', progressive: true, quality: 20);

        // Naming it with uniqe id for the file and stores
        $imageName  = str()->random(15) . '.' . 'jpg';
        #$savePath = public_path('image/users/' . $imageName );
        $tempPath = storage_path('app/temp/' . $imageName);
        //$destination = asset('image/users/' . $imageName );
        
        $jpgEncodedImage->save($tempPath);
        
        \Tinify\setKey(env("API_KEY_FOR_TINIPNG"));

        try {
            $source = \Tinify\fromFile($tempPath);
            $savePath = public_path('image/users/' . $imageName );
            $source->toFile($savePath);
        } catch (\Tinify\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Image optimization failed',
                'error' => $e->getMessage(),
            ], 500);
        }

        unlink($tempPath); // Clean up temporary file
        $destination = asset('image/users/' . $imageName);

        // Stores data in the database
        $validated = $validator->validated();
        $user = User::create([
            'FullName'=> $validated['FullName'],
            'E-Mail'=> $validated['E-Mail'],
            'Phone'=> $validated['Phone'],
            'PositionId'=> $validated['PositionId'],
            'Photo'=> $destination,
        ]);

        return response()->json([
            'success' => true,
            'Users'    => $user,
            'message' => 'User created successfully',
            
        ], 201);   
    }

    /*
     * Middleware for handling user creation POST requests.
     * It processes and forwards the request,
     * if gives any errors sends back to user form 
     * if request was okay, then it's goes to user list.
     */
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
    /*
     * Opens user list stored in the database.
     */
    public function showList(Request $request)
    {
        // adding a page parameter when sending request
        if ($request['page'] == null){ $request['page'] = 1; }

        // Retrives list of users
        $parameters = new Request([
            'page' => $request['page'],
            'count' => 6,
        ]);
        
        $response = $this->getUsers($parameters);
        $users = json_decode($response->getContent(), true);
    
        // Retrives positions list
        $requestPosition = Request::create(route('position.get'), 'GET');
        $reponsePosition = Route::dispatch($requestPosition);
        $positions  = json_decode($reponsePosition->getContent(), true);
        
        // Save possitions as dictionary 
        $PositionMap = [];
        foreach ($positions['positions'] as $position) {
            $PositionMap[$position['id']] = $position['name'];
        }

        // Swaps Users positions id's with their name possition names
        foreach ($users['users']['data'] as &$user) {
            $user['PositionId'] = $PositionMap [$user['PositionId']];
        }
        
        return view('list', ['users'=> $users['users']['data'], 'links' => $users['users']['links'],]);
    }

    /*
     * Opens user creation form 
     */
    public function showForm()
    {
        $requestPosition = Request::create(route('position.get'), 'GET');
        $reponsePosition = Route::dispatch($requestPosition);

        // $requestToken = Request::create(route('token.generate'), 'POST');
        // $reponseToken = Route::dispatch($requestToken);
        
        // Request::create() with post, give's error 419 sessions expired for unknown reasons 
        $reponseToken = app('App\Http\Controllers\AuthController')->generate();
        
        $positions = json_decode($reponsePosition->getContent(), true);
        $token = json_decode($reponseToken->getContent(), true);
        
        return view('form', [
            'positions'=> $positions['positions'],
            'token' => $token['token']
        ]);
    }
}