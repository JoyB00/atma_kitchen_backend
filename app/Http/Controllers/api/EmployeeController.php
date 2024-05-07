<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employees;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index()
    {
        $employee = Employees::with('Users', 'Users', 'Users.Roles', 'Absence')->whereHas('Users', function ($query) {
            $query->where('active', 1);
        })->orderBy('id', 'desc')->get();
        return response([
            'message' => "Retrieve All Employee Successfully",
            'data' => $employee,
        ], 200);
    }
    public function showEmployee()
    {
        $employee = Employees::with('Users', 'Users.Roles', 'Absence')->whereHas('Users', function ($query) {
            $query->where('active', 1)->where('role_id', '!=', 4)->where('role_id', '!=', 1);
        })->get();

        return response([
            'message' => "Retrieve All Employee Successfully",
            'data' => $employee,
        ], 200);
    }

    // STORE SUDAH ADA DI AUTHCONTROLLER (REGISTEREMPLOYEE)

    public function update(Request $request, $id)
    {
        $employee = Employees::find($id);
        if (is_null($employee)) {
            return response([
                'message' => 'Employee Not Found'
            ], 404);
        }

        $validate = Validator::make($request->all(), [
            'role_id' => 'required',
            'fullName' => 'required',
            'phoneNumber' => 'required|max:13|min:10',
            'gender' => 'required',
        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }

        $request['password'] = bcrypt($request->password);

        $employee->users->update($request->all());
        return response([
            'message' => 'Employee Updated',
            'data' => $employee
        ], 200);
    }

    public function show($id)
    {
        $employee = Employees::with('Users')->find($id);
        if (is_null($employee)) {
            return response([
                'message' => 'Employee Not Found'
            ], 404);
        }
        return response([
            'message' => 'A Employee Retrieved',
            'data' => $employee
        ], 200);
    }

    public function changePasswordEmployee(Request $request)
    {
        $user = User::find(auth()->user()->id);
        $oldPassword = $request->oldPassword;
        $newPassword = $request->newPassword;
        if (!password_verify($oldPassword, $user->password)) {
            return response([
                'message' => 'Old Password is incorrect'
            ], 400);
        }

        $user->update([
            'password' => bcrypt($newPassword)
        ]);

        return response([
            'message' => 'Password Changed'
        ], 200);
    }

    public function deactivate($id)
    {
        $employee = Employees::with('Users')->find($id);
        if (is_null($employee)) {
            return response([
                'message' => 'Employee Not Found'
            ], 404);
        }

        $employee->users->update([
            'active' => '0'
        ]);

        return response([
            'message' => 'Employee deactivated'
        ], 200);
    }

    // For testing purposes only
    public function reactivate($id)
    {
        $employee = Employees::with('Users')->find($id);
        if (is_null($employee)) {
            return response([
                'message' => 'Employee Not Found'
            ], 404);
        }

        $employee->users->update([
            'active' => '1'
        ]);

        return response([
            'message' => 'Employee reactivated'
        ], 200);
    }
}
