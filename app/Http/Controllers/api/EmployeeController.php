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
    public function store(Request $request)
    {
        $data = $request->all();
        $user_id = auth()->user()->id;
        $validate = Validator::make($data, [
            'word_start_date' => 'required',
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first(),
            ], 400);
        }
        $data['user_id'] = $user_id;
        $employee = Employees::create($data);
        return response([
            'message' => "Employee Created Successfully",
            'data' => $employee,
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
}
