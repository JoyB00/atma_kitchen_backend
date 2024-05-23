<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;

class PaymentController extends Controller
{
    public function getSnapToken(Request $request)
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $details = $request->input('details', []);

        $items = [];
        foreach ($details as $item) {
            if (is_null($item['hampers_id'])) {
                $items[] = [
                    "price" => $item['product']['product_price'],
                    "quantity" => $item['quantity'],
                    "name" => $item['product']['product_name'],
                ];
            } else if (is_null($item['product_id'])) {
                $items[] = [
                    "price" => $item['hampers']['hampers_price'],
                    "quantity" => $item['quantity'],
                    "name" => $item['hampers']['hampers_name'],
                ];
            }
        }

        $params = [
            'transaction_details' => [
                'order_id' => uniqid(),
                'gross_amount' => $request->amount,
            ],
            "item_details" => $items,
            'customer_details' => [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return response(['snapToken' => $snapToken, $items[0]]);
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 500);
        }
    }
}
