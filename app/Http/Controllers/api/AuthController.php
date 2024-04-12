<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $register = $request->all();

        $validate = Validator::make($register, [
            'nama' => 'required',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required|min:8',
            'no_telp' => 'required|max:13',
            'tanggal_lahir' => 'required',
        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 404);
        }
        $register['id_role'] = 4;
        $register['password'] = bcrypt($request->password);

        $user = User::create($register);

        return response([
            'message' => 'Register Success',
            'user' => $user,
        ], 200);
    }


    public function login(Request $request)
    {
        $login = $request->all();
        $validate = Validator::make($login, [
            'email' => 'required|email:rfc,dns',
            'password' => 'required|min:8',
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors(),
            ], 400);
        }

        if (!Auth::attempt($login)) {
            return response([
                'message' => 'Invalid Credential',
            ], 401);
        }

        /** @var \App\Models\User $user  **/
        $user = Auth::user();
        $token = $user->createToken('Authentication Token')->accessToken;

        return response([
            'message' => 'Authenticated',
            'user' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ], 200);
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response([
            'message' => 'Logged out'
        ]);
    }
}
