<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\HampersDetails;
use App\Models\Ingredients;
use App\Models\Product;
use App\Models\ProductLimits;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
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
        $payments = Transactions::with('Customer.Users')->where(function ($query) {
            $query->where('status', '=', 'notPaid')->orWhere('status', '=', 'alreadyPaid');
        })->get();

        return response([
            'message' => 'Successful retrieval of payment confirmation data.',
            'data' => $payments
        ]);
    }

    public function rejectTransaction($id)
    {
        $data = Transactions::find($id);
        $data->update([
            'status' => 'rejected'
        ]);

        return response([
            'message' => 'Transaction has been successfully rejected.',
            'data' => $data
        ]);
    }

    public function confirmPayment(Request $request)
    {
        $data = $request->all();
        $validate = Validator::make(
            $data,
            [
                'id' => 'required',
                'payment_amount' => 'numeric',
            ],
        );
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }

        $transaction = Transactions::find($data['id']);
        $customer = Customers::find($transaction->customer_id);
        if ($data['payment_amount'] < $transaction->total_price) {
            return response([
                'message' => 'Payment amount is less than the total price.'
            ], 400);
        }
        $data['status'] = 'paymentValid';
        $data['paidoff_date'] = date('Y-m-d H:i:s');
        $data['tip'] = $data['payment_amount'] - $transaction->total_price;

        // handle quota (see TransactionController.php for E-money version)
        if ($data['payment_method'] == '"Cash"') {
            // handle product stock/quota change
            $details = TransactionDetail::where('transaction_id', $request['id'])->get();
            foreach ($details as $item) {
                // if product then ... else hampers
                if (!is_null($item->product_id)) {
                    $product = Product::find($item->product_id);

                    // if product status is ready then reduce ready stock, if not then add product limit(?)
                    if ($item->status_item == 'Ready') {
                        $product->ready_stock = $product->ready_stock - $item->quantity; // reduce ready stock (if status is ready)
                    } else {
                        $limit = ProductLimits::where('production_date', Carbon::parse($transaction->pickup_date)->toDateString())->where('product_id', $item->product_id)->first();
                        if (is_null($limit)) {
                            ProductLimits::create([
                                'product_id' => $item->product_id,
                                'limit_amount' => $product->daily_stock - $item->quantity,
                                'production_date' => $transaction->pickup_date,
                            ]);
                        } else {
                            $limit->update([
                                'limit_amount' => $limit->limit_amount - $item->quantity,
                            ]);
                        }
                    }
                    $product->save();
                } else if (!is_null($item->hampers_id)) {
                    $detailHampers = HampersDetails::where('hampers_id', $item->hampers_id)->get();

                    foreach ($detailHampers as $dh) {
                        if (!is_null($dh->product_id)) {
                            $p = Product::find($dh->product_id);
                            if ($item->status_item == 'Ready') {
                                $p->ready_stock = $p->ready_stock - $item->quantity;
                            } else {
                                $limit = ProductLimits::where('production_date', Carbon::parse($transaction->pickup_date)->toDateString())->where('product_id', $p->id)->first();

                                if (is_null($limit)) {
                                    ProductLimits::create([
                                        'product_id' => $p->id,
                                        'limit_amount' => $p->daily_stock - $item->quantity,
                                        'production_date' => $transaction->pickup_date,
                                    ]);
                                } else {
                                    $limit->update([
                                        'limit_amount' => $limit->limit_amount - $item->quantity,
                                    ]);
                                }
                            }
                            $p->save();
                        } else if (!is_null($dh->ingredient_id)) {
                            $i = Ingredients::find($dh->ingredient_id);
                            $i->update([
                                'quantity' => $i->quantity - 1
                            ]);
                        }
                    }
                }
            }
        }

        $transaction->update($data);
        return response([
            'message' => 'Payment confirmation has been successfully confirmed.',
            'data' => $transaction
        ]);
    }
}
