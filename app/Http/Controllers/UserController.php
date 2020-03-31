<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;

use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class UserController extends Controller
{
    //tambahkan ini
    public function login(Request $request){
        $credentials = $request->only('email','password');
        try{
            if(! $token = JWTAuth::attempt($credentials)){
                return response()->json([
                    'logged' => false,
                    'message' => 'invalid email and password'
                ], 400);
                }
            } catch (Exception $e){
                return response()->json(['error'=>'could_not_create_token'], 500);
            }
            return response()->json([
                "logged" => true,
                "token" => $token,
                "message" => 'Login berhasi'
            ]);
        }
        public function register(Request $request){
            $validator = Validator::make($request->all(), [
                'firstname'=>'required|string|max:250',
                'lastname'=>'required|string|max:250',
                'email'=>'required|string|max:250|unique:users',
                'password'=>'required|string|min:8',
                'password_verify'=>'required|string|min:8',
            ]);
            if($validator->fails()){
                return response()->json($validator->errors()->toJson(),400);
            }
            $user = User::create([
                'firstname'=>$request->get('firstname'),
                'lastname'=>$request->get('lastname'),
                'email'=>$request->get('email'),
                'password'=>Hash::make($request->get('password')),
                'password_verify'=>Hash::make($request->get('password_verify')),
            ]);
            $token = JWTAuth::fromUser($user);
            return response()->json(compact('user','token'), 201);
        }
        public function LoginCheck(){
            try {
                if(!$user = JWTAuth::parseToken()->authenticate()){
                    return response()->json([
                            'auth' 		=> false,
                            'message'	=> 'Invalid token'
                        ]);
                }
            } catch (Tymon\JWTAuth\Exceptions\TokenExpiredException $e){
                return response()->json([
                            'auth' 		=> false,
                            'message'	=> 'Token expired'
                        ], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\TokenInvalidException $e){
                return response()->json([
                            'auth' 		=> false,
                            'message'	=> 'Invalid token'
                        ], $e->getStatusCode());
            } catch (Tymon\JWTAuth\Exceptions\JWTException $e){
                return response()->json([
                            'auth' 		=> false,
                            'message'	=> 'Token absent'
                        ], $e->getStatusCode());
            }
    
             return response()->json([
                     "auth"      => true,
                    "user"    => $user
             ], 201);
        }

        public function logout(Request $request)
        {
    
            if(JWTAuth::invalidate(JWTAuth::getToken())) {
                return response()->json([
                    "logged"    => false,
                    "message"   => 'Logout berhasil'
                ], 201);
            } else {
                return response()->json([
                    "logged"    => true,
                    "message"   => 'Logout gagal'
                ], 201);
            }    
        }
}
