<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Midtrans\Config;
use Midtrans\Snap;
use App\Models\Transactions;

class PaymentController extends Controller
{
    public function getSnapToken(Request $request)
    {
        Config::$serverKey = env('MIDTRANS_SERVER_KEY');
        Config::$isProduction = env('MIDTRANS_IS_PRODUCTION');
        Config::$isSanitized = true;
        Config::$is3ds = true;

        $params = [
            'transaction_details' => [
                'order_id' => uniqid(),
                'gross_amount' => $request->amount,
            ],
            'customer_details' => [
                'first_name' => $request->first_name,
                'last_name' => $request->last_name,
                'email' => $request->email,
                'phone' => $request->phone,
            ],
        ];

        try {
            $snapToken = Snap::getSnapToken($params);
            return response(['snapToken' => $snapToken]);
        } catch (\Exception $e) {
            return response(['error' => $e->getMessage()], 500);
        }
    }

    public function getAllPaymentConfirmation()
    {
        $payments = Transactions::where(function ($query) {
            $query->where('status', '==', 'notPaid')->orwhere('status', '==', 'alreadyPaid');
        })->get();

        return response([
            'message' => 'Successful retrieval of payment confirmation data.',
            'data' => $payments
        ]);
    }

    public function confirmPayment($request)
    {
        $payment = Transactions::find($request->id);
        $payment->status = 'paymentValid';

        $payment->save();

        return response([
            'message' => 'Payment confirmation has been successfully confirmed.',
            'data' => $payment
        ]);
    }
}
