<?php

namespace App\Http\Controllers;
use App\Models\User;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
// use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\ValidationException;
use Auth;

class UserController extends Controller
{
    // public function index(){
    //     $user = users::all();
    //     return response()->json($user);
    //     dd($user);
    // }

    public function index()
    {
        // $users = Auth::guard('api')->user();
        // return response()->json(['data' => $users]);

        $users = User::all(); 

        return response()->json($users);

        // return view('dashboard', ['users' => $users]);
    }


    public function register(Request $request)
    {
        $validatedData = $request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required',
        ]);

        $user = User::create($validatedData);

        // $token = $user->createToken("auth_token_register")->accessToken;
        return response()->json(
            [
                // 'token' =>$token,
                'user' =>$user,
                'message' =>'User Created Successfully',
                'status' => 1
            ]
        );
    }

    // Route::post('/sanctum/token', function (Request $request) {
    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //         'device_name' => 'required',
    //     ]);
     
    //     $user = User::where('email', $request->email)->first();
     
    //     if (! $user || ! Hash::check($request->password, $user->password)) {
    //         throw ValidationException::withMessages([
    //             'email' => ['The provided credentials are incorrect.'],
    //         ]);
    //     }
     
    //     return $user->createToken($request->device_name)->plainTextToken;
    // });

    // public function login(Request $request)
    // {
        // if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
        //     $user = Auth::user();
        //     $resArr = [];
        //     $resArr['token']=$user->createToken('authToken')->accesssToken;
        //     $resArr['name']=$user->name;
            
        //     return response()->json($resArr, 200);
        // }

    //     $request->validate([
    //         'email' => 'required|email',
    //         'password' => 'required',
    //     ]);
        
        
    //     if (Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
    //         $user = Auth::user();
    //         // $token = $user->createToken('authToken')->plainTextToken;
    //             $token = $user->createToken('auth_token_login')->accessToken;

    //         dd($token);
    //         return response()->json(['token' => $token], 200);
    //     }

    //     throw ValidationException::withMessages([
    //         'email' => ['The provided credentials are incorrect.'],
    //     ]);
    // }

    public function login (Request $request) {

        $validator = Validator::make($request->all(), [
                    'email'=>'required','email',
                    'password'=>'required',
        ]);
        
        if ($validator->fails())
        {
            return response(['errors'=>$validator->errors()->all()], 422);
        }

        $user = User::where('email', $request->email)->first();

        if ($user) {
            if (Hash::check($request->password, $user->password)) {

                $token = $user->createToken('auth_token_login')->accessToken;

                // $token = $user->createToken($request->email)->plainTextToken;

                $response = [
                    'token' =>$token,
                    'user' =>$user,
                    'message' =>'Logged In Successfully',
                    'status' => 1
                ];
                return response($response, 200);

            } else {
                $response = ["message" => "Password mismatch"];
                return response($response, 422);
            }

        } else {
            $response = ["message" =>'User does not exist'];
            return response($response, 422);
        }

        // $request->validate([
        //     'email' => 'required|email',
        //     'password' => 'required',
        //     // 'device_name' => 'required',
        // ]);
     
        // $user = User::where('email', $request->email)->first();
     
        // if (! $user || ! Hash::check($request->password, $user->password)) {
        //     throw ValidationException::withMessages([
        //         'email' => ['The provided credentials are incorrect.'],
        //     ]);
        // }
        
        // return $user->createToken($request->device_name)->plainTextToken;
    }

        // $validator = Validator::make($request->all(), [
        //             'email'=>'required','email',
        //             'password'=>'required',
        // ]);
        // if ($validator->fails())
        // {
        //     return response(['errors'=>$validator->errors()->all()], 422);
        // }
        // $user = User::where('email', $request->email)->first();
        // if ($user) {
        //     if (Hash::check($request->password, $user->password)) {
        //         $token = $user->createToken('auth_token_login')->accessToken;
        //         $response = [
        //             'token' =>$token,
        //             'user' =>$user,
        //             'message' =>'Logged In Successfully',
        //             'status' => 1
        //         ];
        //         return response($response, 200);
        //     } else {
        //         $response = ["message" => "Password mismatch"];
        //         return response($response, 422);
        //     }
        // } else {
        //     $response = ["message" =>'User does not exist'];
        //     return response($response, 422);
        // }

    // public function login(Request $request)
    // {
    //     $validatedData = $request->validate([
    //         'email'=>'required','email',
    //         'password'=>'required',
    //     ]);

    //     $user = User::where(['email' => $validatedData['email'],'password' => $validatedData['password']])->first();
    //     // $token = $user->createToken("auth_token")->accessToken;
    //     return response()->json(
    //         [
    //             // 'token' =>$token,
    //             'user' => $user,
    //             'message' => 'Logged In Successfuly',
    //             'status' => 1
    //         ]
    //         );

    //     // echo"<pre>";
    //     // print_r($user);
    // }

    public function logout (Request $request) {
        $token = $request->user()->token();
        $token->revoke();
        $response = ['message' => 'You have been successfully logged out!'];
        return response($response, 200);
    }

    public function getUser($id)
    {
        $users = User::find($id); 
        
        if(is_null($users)){

            return response()->json(
                [
                    'user' => null,
                    'message' => 'User Not Found',
                    'status' => 0
                ]
            );
        }else{
            
            return response()->json(
            [
                'user' => $users,
                'message' => 'User Found',
                'status' => 1
            ]
        );
        }
    }
    
    public function userdetails()
    {
        $users = Auth::guard('api')->user();

        return response()->json(['data' => $users]);
    }


    public function getUserById($id)
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json(['message' => 'User not found'], 404);
        }

        return response()->json($user);
    }
}
