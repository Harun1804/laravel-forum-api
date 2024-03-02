<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Http\Requests\Api\Auth\RegisterRequest;

class AuthController extends ApiController
{
    public function __construct()
    {
        $this->middleware('auth:sanctum')->only('logout');
    }

    public function register(RegisterRequest $request)
    {
        DB::beginTransaction();
        try {
            User::create([
                'name'      => $request->validated('name'),
                'username'  => $request->validated('username'),
                'email'     => $request->validated('email'),
                'password'  => $request->validated('password'),
            ]);
            DB::commit();
            return $this->successResponse(null, 'User registered successfully', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse( $e->getMessage());
        }
    }

    public function login(LoginRequest $request)
    {
        DB::beginTransaction();
        try {
            if(!Auth::attempt($request->validated())) {
                DB::rollBack();
                return $this->errorResponse('Invalid credentials', 401);
            }else{
                $user   = Auth::user();
                $token  = $user->createToken('authToken')->plainTextToken;
                DB::commit();
                return $this->successResponse([
                    'user'  => $user,
                    'type'  => 'Bearer',
                    'token' => $token,
                ], 'User logged in successfully');
            }
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse( $e->getMessage());
        }
    }

    public function logout(Request $request)
    {
        DB::beginTransaction();
        try {
            $request->user()->currentAccessToken()->delete();
            DB::commit();
            return $this->successResponse(null, 'User logged out successfully');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse( $e->getMessage());
        }
    }
}
