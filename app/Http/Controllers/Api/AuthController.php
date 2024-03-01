<?php

namespace App\Http\Controllers\Api;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Api\ApiController;
use App\Http\Requests\Api\Auth\RegisterRequest;

class AuthController extends ApiController
{
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
            return $this->successReponse(null, 'User registered successfully', 201);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->errorResponse( $e->getMessage());
        }
    }
}
