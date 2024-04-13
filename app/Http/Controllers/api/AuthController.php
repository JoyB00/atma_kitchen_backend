<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Models\User;
use App\Mail\MailSend;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $register = $request->all();
        $str = Str::random(30);
        $validate = Validator::make($register, [
            'fullName' => 'required',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required|min:8',
            'phoneNumber' => 'required|max:13|min:10',
            'dateOfBirth' => 'required',
        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 404);
        }
        $register['verify_key'] = $str;
        $register['id_role'] = 4;
        $register['password'] = bcrypt($request->password);

        $user = User::create($register);

        $details = [
            'username' => $request->fullName,
            'website' => 'Atma Kitchen',
            'dateTime' => date('Y-m-d H:i:s'),
            'url' => request()->getHttpHost() . '/register/verify/' . $str
        ];

        Mail::to($request->email)->send(new MailSend($details));

        return response([
            'message' => 'Registered, verify your email address to login',
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

    public function verify($verify_key)
    {
        $keyCheck = User::select('verify_key')->where('verify_key', $verify_key)->exists();
        if ($keyCheck) {
            $user = User::where('verify_key', $verify_key)->update([
                'active' => 1,
                'email_verified_at' => date('Y-m-d H:i:s'),
            ]);

            return "Verification Successful, Your account is active now";
        } else {
            return "Verification Failed, Key not found";
        }
    }

    public function logout(Request $request)
    {
        $request->user()->token()->revoke();

        return response([
            'message' => 'Logged out'
        ]);
    }
}
