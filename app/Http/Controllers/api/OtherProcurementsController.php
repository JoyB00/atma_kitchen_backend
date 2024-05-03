<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Employees;
use App\Models\OtherProcurements;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class OtherProcurementsController extends Controller
{
    public function index()
    {
        $data = OtherProcurements::orderBy('procurement_date', 'desc')->get();
        return response([
            'message' => 'All Other Procurement Retrieved',
            'data' => $data,
        ], 200);
    }

    public function getProcurement($id)
    {
        $procurement = OtherProcurements::find($id);
        if (is_null($procurement)) {
            return response([
                'message' => 'Procurement Not Found'
            ], 404);
        }
        return response([
            'message' => 'Retrieved Procurement Successfully',
            'data' => $procurement
        ], 200);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'item_name' => 'required',
            'price' => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:1',
            'procurement_date' => 'required',
            'total_price' => 'required|numeric|min:1',
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }

        $employee = Employees::where('user_id', auth()->user()->id)->first();
        $storeData['employee_id'] = $employee->id;
        $otherProcurement = OtherProcurements::create($storeData);
        return response([
            'message' => "Procurement created successfully",
            'data' => $otherProcurement,
        ], 200);
    }
    public function update(Request $request, $id)
    {
        $procurement = OtherProcurements::find($id);
        if (is_null($procurement)) {
            return response([
                'message' => 'Procurement Not Found'
            ], 404);
        }
        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'item_name' => 'required',
            'price' => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:1',
            'procurement_date' => 'required',
            'total_price' => 'required|numeric|min:1',
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }

        $procurement->update($updateData);

        return response([
            'message' => "Procurement created successfully",
            'data' => $procurement,
        ], 200);
    }

    public function destroy($id)
    {
        $procurement = OtherProcurements::find($id);
        if (is_null($procurement)) {
            return response([
                'message' => "Procurement not found",
            ], 404);
        }
        $procurement->delete();
        return response([
            'message' => "Delete Procurement Successfully",
            'data' => $procurement,
        ], 200);
    }
}
