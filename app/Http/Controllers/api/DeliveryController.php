<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deliveries;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryController extends Controller
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

    public function update(Request $request, $id)
    {
        $data = $request->all();
        $delivery = Deliveries::find($id);
        if (is_null($delivery)) {
            return response([
                'message' => 'Data not found',
                'data' => $delivery
            ], 404);
        }
        $delivery->update($data);
        return response([
            'message' => 'The delivery has been successfully updated.',
            'data' => $delivery
        ], 200);
    }
}
