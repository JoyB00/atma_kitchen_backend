<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Attendances;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AttendanceController extends Controller
{
    public function index()
    {
        $attendence = Attendances::get();
        return response([
            'message' => 'All Attendandes Retrived',
            "data" => $attendence
        ], 200);
    }
    public function store(Request $request)
    {
        $data = $request->all();
        $validate = Validator::make($data, [
            'attendance_date' => 'required',
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first(),
            ], 400);
        }
        $data['employee_id'] = auth()->user()->id;
        $data['is_absence'] = true;
        $attendance = Attendances::create($data);

        return response([
            'message' => 'Create Attendance Successfully',
            'data' => $attendance
        ], 200);
    }
    public function update(Request $request, $id)
    {
        $attendance = Attendances::find($id);
        if (is_null($attendance)) {
            return response([
                'message' => 'Attendance Not Found'
            ]);
        }

        $data = $request->all();
        $validate = Validator::make($data, [
            'attendance_date' => 'required',
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }

        $attendance->update($data);
        return response([
            'message' => 'Attendance Updated Successfully',
            'data' => $attendance
        ], 200);
    }
    public function destroy($id)
    {
        $attendence = Attendances::find($id);
        if (is_null($attendence)) {
            return response([
                'message' => 'Attendance Not Found'
            ]);
        }

        if ($attendence->delete()) {
            return response([
                'message' => 'Attendance Deleted Successfully',
                'data' => $attendence
            ], 200);
        }

        return response([
            'message' => 'Delete Attendance Failed',
            'data' => null,
        ], 400);
    }
}
