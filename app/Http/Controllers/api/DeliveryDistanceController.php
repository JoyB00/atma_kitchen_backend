<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deliveries;
use App\Models\Transactions;
use Illuminate\Http\Request;

class DeliveryDistanceController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();
        $delivery = Deliveries::create($data);
        $transaction = Transactions::find($data['transaction_id']);
        $transaction->delivery_id = $delivery->id;
        $transaction->save();

        return response([
            'message' => 'The delivery has been successfully added.',
            'data' => $delivery
        ], 200);
    }

    public function show()
    {
        $deliveries = Transactions::with('Delivery')->whereHas('Delivery', function ($query) {
//            $query->where('delivery_method', '==', 'Delivery Courier');
            $query->where('distance', '==', null);
        })->get();

        return response([
            'message' => 'All data Retrievied',
            'data' => $deliveries,
        ], 200);
    }
}
