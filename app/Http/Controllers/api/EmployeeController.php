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
        $employee = Employees::get();
        $user = $employee->Users();

        $result = [$employee, $user];
        return response([
            'message' => "Retrieve All Employee Successfully",
            'data' => $result,
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

        $validate = Validator::make($request, [
            'role_id' => 'required',
            'fullName' => 'required',
            'email' => 'required|email:rfc,dns|unique:users',
            'password' => 'required|min:8',
            'phoneNumber' => 'required|max:13|min:10',
            'gender' => 'required',
            'birth_date' => 'required',
        ]);
        $request['password'] = bcrypt($request->password);

        $employee->user->update($request->all());
        return response([
            'message' => 'Employee Updated',
            'data' => $employee
        ], 200);
    }

    public function show($id)
    {
        $employee = Employees::find($id);
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

    public function delete($id)
    {
        $employee = Employees::find($id);
        if (is_null($employee)) {
            return response([
                'message' => 'Employee Not Found'
            ], 404);
        }

        $employee->user->update([
            'active' => '0'
        ]);

        return response([
            'message' => 'Employee deactivated'
        ], 200);
    }

    // For testing purposes only
    public function reactivate($id)
    {
        $employee = Employees::find($id);
        if (is_null($employee)) {
            return response([
                'message' => 'Employee Not Found'
            ], 404);
        }

        $employee->user->update([
            'active' => '1'
        ]);

        return response([
            'message' => 'Employee reactivated'
        ], 200);
    }
}
