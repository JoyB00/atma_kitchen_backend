<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Penitip;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class PenitipController extends Controller
{
    public function index()
    {
        $penitip = Penitip::get();
        if (is_null($penitip)) {
            return response([
                'message' => 'No Data Found',
                'data' => null
            ], 404);
        }
        return response([
            'message' => 'All Penitip Retrivied',
            'data' => $penitip
        ], 200);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'nama_penitip' => 'required',
            'no_telp' => 'required|max:13'
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }

        $penitip = Penitip::create($storeData);
        return response([
            'message' => 'Penitip Added Successfully',
            'data' => $penitip,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $penitip = Penitip::find($id)->first();
        if (is_null($penitip)) {
            return  response([
                'message' => "Penitip Not Found",
                'data' => null
            ], 404);
        }
        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'nama_penitip' => 'required',
            'no_telp' => 'required|max:13'
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }

        $penitip->update($updateData);
        return response([
            'message' => 'Penitip Updated Successfully',
            'data' => $penitip,
        ], 200);
    }

    public function destroy($id)
    {
        $penitip = Penitip::find($id)->first();
        if (is_null($penitip)) {
            return response([
                'message' => 'Penitip Not Found',
                'data' => null
            ], 404);
        }
        if ($penitip->delete()) {
            return response([
                'message' => 'Penitip Deleted Successfully',
                'data' => $penitip
            ], 200);
        }
        return  response([
            'message' => 'Delete Penitip Failed',
            'data' => null,
        ], 400);
    }
}
