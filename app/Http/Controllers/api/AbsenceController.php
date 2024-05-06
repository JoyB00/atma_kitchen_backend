<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AbsenceController extends Controller
{
    public function index()
    {
        $absence = Absence::with('Employees.Users')->get();
        return response([
            'message' => 'All Absence List Retrived',
            "data" => $absence
        ], 200);
    }

    public function show($id)
    {
        $absence = Absence::find($id);
        if (is_null($absence)) {
            return response([
                'message' => 'Absence Not Found'
            ]);
        }

        return response([
            'message' => 'Absence Retrived',
            'data' => $absence
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validate = Validator::make($data, [
            'employee_id' => 'required',
            'absence_date' => 'required',
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first(),
            ], 400);
        }

        $absence = Absence::create($data);
        return response([
            'message' => 'Absenced Successfully',
            'data' => $absence
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $absence = Absence::find($id);
        if (is_null($absence)) {
            return response([
                'message' => 'Absence Not Found'
            ]);
        }

        $data = $request->all();
        $validate = Validator::make($data, [
            'absence_date' => 'required',
        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }

        $absence->update($data);
        return response([
            'message' => 'Attendance Updated Successfully',
            'data' => $absence
        ], 200);
    }

    public function destroy($id)
    {
        $absence = Absence::find($id);
        if (is_null($absence)) {
            return response([
                'message' => 'Absence` Not Found'
            ]);
        }

        if ($absence->delete()) {
            return response([
                'message' => 'Absence Deleted Successfully',
                'data' => $absence
            ], 200);
        }

        return response([
            'message' => 'Delete Absence Failed',
            'data' => null,
        ], 400);
    }
}
