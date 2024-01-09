<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class UserApiController extends Controller
{
    public function index()
    {
        $users = User::all(); 
        return response()->json($users);
    }

    public function register(Request $request)
    {
        
        $validatedData = $request->validate([
            'name'=>'required',
            'email'=>'required|email',
            'password'=>'required',
        ]);

        $user = User::create($validatedData);
        $token = $user->createToken("auth_token")->accessToken;
        return response()->json(
            [
                'token' =>$token,
                'user' =>$user,
                'mesage' =>'User Created Successfully',
                'status' => 1
            ]
            );

    }

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
                // $token = $user->createToken($request->device_name)->plainTextToken;
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
    }

    public function getUser(Request $request)
    {
        $users = User::all();
        return view('dashboard', compact('users'));
        // return $request->user();
    }
    
    public function updateStatus(User $user)
    {
        $user->is_active = !$user->is_active;
        $user->save();
    
        return response()->json(['message' => 'User status updated successfully']);
    }
}

