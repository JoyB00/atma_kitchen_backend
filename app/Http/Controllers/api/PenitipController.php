<?php

namespace App\Http\Controllers;

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
}
