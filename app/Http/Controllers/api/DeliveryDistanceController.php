<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryDistanceController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();
        $validate = Validator::make(
            $data,
            [
                'id' => 'required',
                'distance' => 'required',
            ],
        );
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }

        // set shipping cost
        $data['shipping_cost'] = 0;

        return response([
            'message' => 'The delivery distance has been successfully added.',
//            'data' => $delivery
        ], 200);
    }

    public function show()
    {
        $deliveries = Transactions::with('Delivery')->whereHas('Delivery', function ($query) {
            $query->where('distance', '==', null)->where('delivery_method', '==', 'Delivery Courier');
        })->get();
//        $deliveries = Deliveries::where('distance', null)->where('delivery_method', 'Delivery Courier')->get();

        return response([
            'message' => 'All data Retrieved',
            'data' => $deliveries,
        ], 200);
    }
}
