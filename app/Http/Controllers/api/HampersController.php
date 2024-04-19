<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hampers;
use App\Models\HampersDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HampersController extends Controller
{
    public function index()
    {
        $hampers = Hampers::get();
        $hampers_detail = HampersDetails::with('Hampers', 'Product', 'Ingredients')->get();
        return response([
            'message' => 'All Hampers Retrivied',
            'data' => [
                'hampers' => $hampers,
                'hampersDetail' => $hampers_detail
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validate = Validator::make($data, [
            'hampers_name' => 'required',
            'hampers_price' => 'required|numeric',
            'quantity' => 'required|number'
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first(),
            ], 400);
        }

        $hampers = Hampers::create($data);
        return response([
            'message' => 'Hampers Created Successfully',
            'data' => $hampers
        ]);
    }

    public function storeDetail(Request $request)
    {
        $data =  $request->all();
        if (!isset($data['product_id']) && !isset($data['ingredient_id'])) {
            return response([
                'message' => "Please fill in the product or ingredient"
            ], 400);
        }

        $last_hampers = Hampers::latest();
        $data['hampers_id'] = $last_hampers->id;
    }
}
