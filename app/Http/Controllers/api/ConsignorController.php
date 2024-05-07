<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Consignors;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ConsignorController extends Controller
{
    public function index()
    {
        $consignor = Consignors::where('active', 1)->orderBy('id','desc')->get();
        if (is_null($consignor)) {
            return response([
                'message' => 'No Data Found',
                'data' => null
            ], 404);
        }
        return response([
            'message' => 'All Consignor Retrivied',
            'data' => $consignor
        ], 200);
    }
    public function getConsignor($id)
    {
        $consignor = Consignors::find($id);
        $product = Product::where('consignor_id', $id)->get();
        if (is_null($consignor)) {
            return response([
                'message' => 'No Data Found',
                'data' => null
            ], 404);
        }
        return response([
            'message' => 'All Consignor Retrivied',
            'data' => [
                'consignor' => $consignor,
                'product' => $product
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'consignor_name' => 'required',
            'phone_number' => 'required|min:11'
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }

        $consignor = Consignors::create($storeData);
        return response([
            'message' => 'Consignor Added Successfully',
            'data' => $consignor,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $consignor = Consignors::find($id);
        if (is_null($consignor)) {
            return  response([
                'message' => "Consignor Not Found",
                'data' => null
            ], 404);
        }
        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'consignor_name' => 'required',
            'phone_number' => 'required|min:11'
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }

        $consignor->update($updateData);
        return response([
            'message' => 'Consignor Updated Successfully',
            'data' => $consignor,
        ], 200);
    }

    public function destroy($id)
    {
        $consignor = Consignors::find($id)->first();
        $product =  Product::where('consignor_id', $id)->get();
        if (is_null($consignor)) {
            return response([
                'message' => 'Consignor Not Found',
                'data' => null
            ], 404);
        }
        if ($consignor->delete()) {
            foreach ($product as $p) {
                $p->delete();
            }
            return response([
                'message' => 'Consignor Deleted Successfully',
                'data' => $consignor
            ], 200);
        }
        return  response([
            'message' => 'Delete Consignor Failed',
            'data' => null,
        ], 400);
    }
    public function disableConsignor($id)
    {
        $consignor = Consignors::find($id);

        if (is_null($consignor)) {
            return response([
                'message' => 'Consignor Not Found',
                'data' => null
            ], 404);
        }
        $consignor['active'] = 0;
        $consignor->save();
        return response([
            'message' => 'Consignor Disabled Successfully',
            'data' => $consignor
        ], 200);
    }
}
