<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Carts;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use App\Models\Customers;
use App\Models\Hampers;
use App\Models\HampersDetails;
use App\Models\Ingredients;
use App\Models\Product;
use App\Models\ProductLimits;
use Carbon\Carbon;

class TransactionController extends Controller
{
    public function getOrderHistory($id)
    {
        $orders = Transactions::where('customer_id', $id)->get();

        return response([
            'message' => 'All data Retrievied',
            'data' => $orders,
        ], 200);
    }
    public function getDetailOrder($id)
    {
        $transaction = Transactions::find($id);
        $detailOrders = TransactionDetail::with('Product', 'Hampers')->where('transaction_id', $id)->get();

        return response([
            'message' => 'All data Retrievied',
            'data' => [
                'transaction' => $transaction,
                'details' => $detailOrders
            ]
        ], 200);
    }
    public function getDetailOrderAuth($id)
    {
        $transaction = Transactions::with('Customer', 'Customer.Users', 'Customer.BalanceHistory', 'Customer.Addresses', 'Employee', 'Employee.Users', 'Delivery')->where('id', $id)->first();

        if (is_null($transaction)) {
            return response([
                'message' => 'Page Not Found'
            ], 404);
        }

        $detailOrders = TransactionDetail::with('Product', 'Hampers')->where('transaction_id', $id)->get();
        $customer = Customers::find($transaction->customer_id);

        if ($customer->user_id != auth()->user()->id) {
            return response([
                'message' => 'Cannot Access This Page'
            ], 400);
        }

        $points = 0;
        $temp_total_price = $transaction->total_price;
        if ($temp_total_price >= 1000000) {
            $points += (int)($transaction->total_price / 1000000) * 200;
            $temp_total_price = $temp_total_price % 1000000;
        }

        if ($temp_total_price >= 500000) {
            $points += (int)($temp_total_price / 500000) * 75;
            $temp_total_price = $temp_total_price % 500000;
        }

        if ($temp_total_price >= 100000) {
            $points += (int)($temp_total_price / 100000) * 15;
            $temp_total_price = $temp_total_price % 100000;
        }

        if ($temp_total_price >= 10000) {
            $points += (int)($temp_total_price / 10000);
        }

        return response([
            'message' => 'All data Retrievied',
            'data' => [
                'transaction' => $transaction,
                'details' => $detailOrders,
                'getPoint' => $points,
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $cart = Carts::where('order_date', $data['order_date'])->get();
        $customer = Customers::where('user_id', auth()->user()->id)->first();
        $transaction = Transactions::create([
            'order_date' => Carbon::now()->toDateTimeString(),
            'pickup_date' => $data['order_date'],
            'customer_id' => $customer->id,
            'status' => 'notPaid',
            'total_price' => $data['total']
        ]);
        foreach ($data['data'] as $item) {
            if (!is_null($item['product_id'])) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'status_item' => $item['status_item'],
                    'quantity' => $item['quantity'],
                    'price' => $item['products']['product_price'],
                    'total_price' => $item['total_price']
                ]);
            } else if (!is_null($item['hampers_id'])) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'hampers_id' => $item['hampers_id'],
                    'status_item' => $item['status_item'],
                    'quantity' => $item['quantity'],
                    'price' => $item['hampers']['hampers_price'],
                    'total_price' => $item['total_price']
                ]);
            }
        }

        foreach ($cart as $item) {
            $item->delete();
        }

        return response([
            'message' => 'Transaction Added Successfully',
            'data' => [
                'transaction' => $transaction,
            ]
        ], 200);
    }

    public function paymentCustomer(Request $request, $id)
    {
        $data = $request->all();
        $transaction = Transactions::find($id);
        $transaction->paidoff_date = Carbon::now();
        $transaction->payment_method = $data['payment_method'];
        $transaction->used_point = $data['point'];
        $transaction->earned_point = $data['point_earned'];
        $transaction->total_price = $data['total_price'];
        $transaction->status = 'alreadyPaid';
        $transaction->save();

        $customer = Customers::find($transaction->customer_id);
        $customer->point = $customer->point - $data['point'] + $data['point_earned'];

        $customer->save();

        $details = TransactionDetail::where('transaction_id', $id)->get();

        foreach ($details as $item) {
            if (!is_null($item->product_id)) {
                $product = Product::find($item->product_id);
                if ($item->status_item == 'Ready') {
                    $product->ready_stock = $product->ready_stock - $item->quantity;
                } else {
                    $limit = ProductLimits::where('production_date', Carbon::parse($transaction->pickup_date)->toDateString())->where('product_id', $item->product_id)->first();

                    if (is_null($limit)) {
                        ProductLimits::create([
                            'product_id' =>  $item->product_id,
                            'limit_amount' => $product->daily_stock - $item->quantity,
                            'production_date' => $transaction->pickup_date,
                        ]);
                    } else {
                        // $limit->limit_amount = $limit->limit_amount - $item->quantity;
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
                                    'product_id' =>  $p->product_id,
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

        return response([
            'message' => 'Payment Successfull',
            'data' => $transaction
        ], 200);
    }

    public function searchProductNameInTransactions($term)
    {
        $customerId = Customers::where('user_id', auth()->user()->id)->first();
        $transaction = Transactions::where('customer_id', $customerId)->get();
        $transactionItemSize = count($transaction);
        $filteredList = [];
        for ($i = 0; $i < $transactionItemSize; $i++) {
            $filtered = TransactionDetail::with('Product', 'Hampers')->where('transaction_id', $transaction[$i]->id)->whereHas('Product', function ($query) use ($term) {
                $query->where('product_name', 'like', '%' . $term . '%');
            })->get();
            if (count($filtered) > 0) {
                array_push($filteredList, $filtered);
            }
        }

        return response([
            'message' => 'All data Retrievied',
            'data' => $filteredList,
        ], 200);
    }
}
