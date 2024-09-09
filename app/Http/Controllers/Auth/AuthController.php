<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use App\Http\Resources\UserResource;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api', ['except' => ['login']]);
    }
    /**
     * authenticates a user with their email and password 
     *
     * @param LoginRequest $request 
     *
     * @return response  of the status of operation : message the user data and the token
     */
    public function login(LoginRequest $request)
    {
        $credentials = $request->only('email', 'password');
        $token = JWTAuth::attempt($credentials);
        if (!$token) {
            return response()->json([
                'status' => 'error',
                'message' => 'البيانات المدخلة خاطئة',
            ], 401);
        }

        $user = User::find(Auth::user()->user_id);
        Auth::login($user);
        $user = UserResource::make($user);

        return response()->json([
            'status' => 'success',
            'data' => [
                'user' =>  $user
            ],
            'message' => 'تم تسجيل الدخول بنجاح',
            'authorisation' => [
                'token' => $token,
                'type' => 'bearer',
            ]
        ], 200);
    }

    /** 
     *invalidates the user Auth token and generates a new token
     *
     *
     * @return response  of the status of operation : message the user data and the  new token
     */
    public function refresh()
    {
        return response()->json([
            'status' => 'success',
            'user' => UserResource::make(Auth::user()),
            'authorisation' => [
                'token' => Auth::refresh(),
                'type' => 'bearer',
            ]
        ]);
    }

    /**
     * invalidates the user Auth token
     *
     * @param Request $request 
     *
     * @return response  of the status of operation : message 
     */
    public function logout(Request $request)
    {
        Auth::logout();
        return response()->json([
            'status' => 'success',
            'message' => 'تم تسجيل الخروج بنجاح'
        ], 200);
    }
}
