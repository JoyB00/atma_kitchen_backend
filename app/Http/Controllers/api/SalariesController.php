<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employees;
use App\Models\Salaries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class SalariesController extends Controller
{
    public function index()
    {
        $salaries = Salaries::with('Employees', 'Employees.Users')->get();
        return response([
            'message' => 'All Salaries Retrivied',
            'data' => $salaries
        ], 200);
    }

    public function getDetailSalary($id)
    {
        $employee = Employees::with('Users', 'Users', 'Users.Roles', 'Attendances')->find($id);
        $salary =  Salaries::where("employee_id", $id)->get();
        return response([
            'message' => 'All Detail Salaries Retrivied',
            'data' => [
                'employee' => $employee,
                'salary' => $salary
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validate = Validator::make($data, [
            'pay_date' => 'required|date',
            'daily_salary' => 'numeric|min:0',
            'bonus' => 'numeric|min:0',
            'total_salary' => 'required|numeric',
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }
        $data["employee_id"] = $request->employee_id;
        $salary = Salaries::create($data);
        return response([
            'message' => 'Salary Created Successfully!',
            'data' => $salary
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $salary = Salaries::find($id);
        if (is_null($salary)) {
            return response([
                'message' => 'Salary Not Found',
                'data' => null
            ], 404);
        }

        $data = $request->all();
        $validate = Validator::make($data, [
            'pay_date' => 'required|date',
            'daily_salary' => 'numeric|min:0',
            'bonus' => 'numeric|min:0',
            'total_salary' => 'required|numeric',
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }

        $salary->update($data);
        return response([
            'message' => 'Salary Updated Successfully',
            'data' => $salary
        ], 200);
    }

    public function destroy($id)
    {
        $salary = Salaries::find($id);
        if (is_null($salary)) {
            return response([
                'message' => 'Salary Not Found',
                'data' => null
            ], 404);
        }
        if ($salary->delete()) {
            return response([
                'message' => 'Salary Deleted Successfully',
                'data' => $salary
            ], 200);
        }
        return  response([
            'message' => 'Delete Salary Failed',
            'data' => null,
        ], 400);
    }
}
