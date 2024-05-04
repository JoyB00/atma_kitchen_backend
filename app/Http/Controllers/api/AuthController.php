<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use App\Mail\MailSend;
use App\Mail\ForgotPasswordMail;
use App\Models\Customers;
use App\Models\Employees;
use App\Models\User;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function register(Request $request)
    {
        $register = $request->all();
        $str = Str::random(100);
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

    public function employeeRegister(Request $request)
    {
        $register = $request->all();
        $str = Str::random(100);
        $validate = Validator::make($register, [
            'role_id' => 'required',
            'fullName' => 'required',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required|min:8',
            'phoneNumber' => 'required|max:13|min:10',
            'gender' => 'required',
            'dateOfBirth' => 'required',
        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 404);
        }
        $register['verify_key'] = $str;
        $register['active'] = 1;
        $register['created_at'] = date('Y-m-d H:i:s');
        $register['updated_at'] = date('Y-m-d H:i:s');
        $register['password'] = bcrypt($request->password);

        $user = User::create($register);
        $employee = Employees::create([
            'user_id' => $user->id,
            'work_start_date' => date('Y-m-d H:i:s'),
        ]);


        return response([
            'message' => 'Congratulations, your account has been successfully created',
            'data' => [
                'user' => $user,
                'employee' => $employee
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



    public function verifyEmail(Request $request)
    {
        $email = $request->email;
        $user = User::where('email', $email)->first();
        if (is_null($user)) {
            return response([
                'message' => 'Email Not Found'
            ], 404);
        }
        $Verification_code = mt_rand(100000, 999999);
        $user['verification_code'] = $Verification_code;
        $user->save();

        $details = [
            'username' => $user["fullName"],
            'website' => 'Atma Kitchen',
            'dateTime' => date('Y-m-d H:i:s'),
            'verification_code' => $Verification_code
        ];

        Mail::to($request->email)->send(new ForgotPasswordMail($details));

        return response([
            'message' => 'Email Sent, to verify your Email',
            'data' => $user,
        ], 200);
    }
    public function verifyCode(Request $request)
    {
        $verification_code = $request->verification_code;
        $user = User::where('verification_code', $verification_code)->first();
        if (is_null($user)) {
            return response([
                'message' => 'Verification Code Not Valid'
            ], 404);
        }

        $token = $user->createToken('Authentication Token')->accessToken;
        $user['verification_code'] = null;
        $user->save();

        return response([
            'message' => 'Verfication Success, Please Change your Password ',
            'data' => $user,
            'token_type' => 'Bearer',
            'access_token' => $token
        ], 200);
    }

    public function changePassword(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'password' => 'required|min:8',
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first(),
            ], 400);
        }
        $password = $request->password;
        $user = User::where('id', auth()->user()->id)->first();
        $user['password'] = $password;
        $user->save();
        $request->user()->token()->revoke();
        return response([
            "message" => "Password Changed Successfully",
            "data" => $user
        ], 200);
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
