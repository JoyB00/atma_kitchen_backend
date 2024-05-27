<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deliveries;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryDistanceController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();
        $delivery = Deliveries::find($data['id']);

        $validate = Validator::make(
            $data,
            [
                'id' => 'required',
                'distance' => 'required|numeric',
            ],
        );
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }
        if ($data['distance'] <= 0.0) {
            return response([
                'message' => 'Distance must be greater than 0'
            ], 400);
        }

        $distance = $data['distance'];
        // set shipping cost
        if ($distance < 5) {
            $shippingCost = 10000;
        } elseif ($distance < 10) {
            $shippingCost = 15000;
        } elseif ($distance <= 15) {
            $shippingCost = 20000;
        } else {
            $shippingCost = 25000;
        }
        $data['shipping_cost'] = $shippingCost;

        $delivery->update($data);
        return response([
            'message' => 'The delivery distance and shipping cost has been successfully added.',
            'data' => $delivery
        ], 200);
    }

    public function show()
    {
        $deliveries = Transactions::with('Delivery')->where('delivery_id', '!=', null)->whereHas('Delivery', function ($query) {
            $query->where('distance', null)->where('delivery_method', 'Delivery Courier');
        })->get();

        return response([
            'message' => 'All data Retrieved',
            'data' => $deliveries,
        ], 200);
    }
}
