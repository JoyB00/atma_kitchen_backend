<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BalanceHistories;
use App\Models\Customers;
use App\Models\Deliveries;
use App\Models\HampersDetails;
use App\Models\Ingredients;
use App\Models\Product;
use App\Models\ProductLimits;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionConfirmationController extends Controller
{
    public function MoConfirmation(Request $request)
    {
        $data = $request->all();

        $transaction = Transactions::find($data['id']);
        $customer = Customers::find($transaction->customer_id);
        if ($data['status'] == 'accepted') {
            $transaction->status = 'accepted';
            $customer->point = $customer->point -  $transaction->used_point + $transaction->earned_point;
            $transaction->save();
            $customer->save();
        } else {
            // handle product stock/quota change
            $details = TransactionDetail::where('transaction_id', $data['id'])->get();
            foreach ($details as $item) {
                // if product then ... else hampers
                if (!is_null($item->product_id)) {
                    $product = Product::find($item->product_id);

                    // if product status is ready then reduce ready stock, if not then add product limit(?)
                    if ($item->status_item == 'Ready') {
                        $product->ready_stock = $product->ready_stock + $item->quantity; // reduce ready stock (if status is ready)
                    } else {
                        $limit = ProductLimits::where('production_date', Carbon::parse($transaction->pickup_date)->toDateString())->where('product_id', $item->product_id)->first();
                        $limit->update([
                            'limit_amount' => $limit->limit_amount + $item->quantity,
                        ]);
                    }
                    $product->save();
                } else if (!is_null($item->hampers_id)) {
                    $detailHampers = HampersDetails::where('hampers_id', $item->hampers_id)->get();
                    foreach ($detailHampers as $dh) {
                        if (!is_null($dh->product_id)) {
                            $p = Product::find($dh->product_id);
                            if ($item->status_item == 'Ready') {
                                $p->ready_stock = $p->ready_stock + $item->quantity;
                            } else {
                                $limit = ProductLimits::where('production_date', Carbon::parse($transaction->pickup_date)->toDateString())->where('product_id', $p->id)->first();
                                $limit->update([
                                    'limit_amount' => $limit->limit_amount + $item->quantity,
                                ]);
                            }
                            $p->save();
                        } else if (!is_null($dh->ingredient_id)) {
                            $i = Ingredients::find($dh->ingredient_id);
                            $i->update([
                                'quantity' => $i->quantity + 1
                            ]);
                        }
                    }
                }
            }
            $transaction->status = 'rejected';
            // $customer->point = $customer->point -  $transaction->used_point - $transaction->earned_point;
            $subtotal = 0;
            foreach ($details as $item) {
                $subtotal = $subtotal + $item->total_price;
            }
            $delivery = Deliveries::find($transaction->delivery_id);
            if (!is_null($delivery->shipping_cost)) {
                $customer->nominal_balance =  $customer->nominal_balance + $subtotal + $delivery->shipping_cost;
            } else {
                $customer->nominal_balance =  $customer->nominal_balance + $subtotal;
            }
            $transaction->save();
            $customer->save();
            BalanceHistories::create([
                'customer_id' => $customer->id,
                'nominal_balance' => $transaction->total_price,
                'bank_name' => 'BRI',
                'account_number' => '8769123441231',
                'date' => Carbon::now()->toDateString(),
                'detail_information' => 'Refund from online purchase'
            ]);
        }

        return response([
            'message' => 'Transaction Updated',
            'data' => $data
        ], 200);
    }
}
