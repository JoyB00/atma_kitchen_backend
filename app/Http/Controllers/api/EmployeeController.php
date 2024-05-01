<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employees;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class EmployeeController extends Controller
{
    public function index()
    {
        $employee = Employees::get();
        return response([
            'message' => "Retrieve All Employee Successfully",
            'data' => $employee,

        ], 200);
    }

    public function update(Request $request, $id)
    {
        $employee = Employees::find($id);
        if (is_null($employee)) {
            return response([
                'message' => 'Employee Not Found'
            ], 404);
        }

        $employee->update($request->all());
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
        $employee->delete();
        return response([
            'message' => 'Employee Deleted'
        ], 200);
    }
}
