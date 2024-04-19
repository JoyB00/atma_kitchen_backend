<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\MailSend;
use App\Models\Customers;
use App\Models\User;
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
        $register['created_at'] = date('Y-m-d H:i:s');
        $register['updated_at'] = date('Y-m-d H:i:s');
        $register['role_id'] = 4;
        $register['password'] = bcrypt($request->password);

        $user = User::create($register);
        $customer = Customers::create([
            'user_id' => $user->id,
            'point' => 0,
            'nominal_balance' => 0,
        ]);

        $details = [
            'username' => $request->fullName,
            'website' => 'Atma Kitchen',
            'dateTime' => date('Y-m-d H:i:s'),
            'url' => request()->getHttpHost() . '/register/verify/' . $str
        ];

        Mail::to($request->email)->send(new MailSend($details));

        return response([
            'message' => 'Registered, verify your email address to login',
            'data' => [
                'user' => $user,
                'customer' => $customer
            ],
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
                'message' => $validate->errors()->first(),
            ], 400);
        }

        if (!Auth::attempt($login)) {
            return response([
                'message' => 'Email or Password Invalid',
            ], 401);
        }


        /** @var \App\Models\User $user  **/
        $user = Auth::user();
        if ($user->active != 1) {
            return response([
                'message' => 'Your account has not been verified. Please verify you account first'
            ]);
        }
        $token = $user->createToken('Authentication Token')->accessToken;

        return response([
            'message' => 'Login Successful',
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
            'message' => 'Logged out',
            // 'data' => $user
        ], 200);
    }
}
