<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Carts;
use App\Models\Customers;
use App\Models\Hampers;
use App\Models\HampersDetails;
use App\Models\Ingredients;
use App\Models\Product;
use App\Models\ProductLimits;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TransactionController extends Controller
{

    public function index()
    {
        $orders = Transactions::with('Delivery', 'Customer', 'Customer.Users', 'Customer.BalanceHistory', 'Customer.Addresses', 'Employee', 'TransactionDetails', 'TransactionDetails.Product', 'TransactionDetails.Hampers')->orderBy('id', 'desc')->get();

        return response([
            'message' => 'All data Retrievied',
            'data' => $orders,
        ], 200);
    }
    public function getOrderConfirmation()
    {
        $orders = Transactions::with('Delivery', 'Customer', 'Customer.Users', 'Customer.BalanceHistory', 'Customer.Addresses', 'Employee', 'TransactionDetails', 'TransactionDetails.Product', 'TransactionDetails.Hampers')->where('status', 'paymentValid')->orderBy('id', 'desc')->get();

        return response([
            'message' => 'All data Retrievied',
            'data' => $orders,
        ], 200);
    }

    public function getOrderHistory($id)
    {
        $orders = Transactions::with('Delivery', 'Customer', 'TransactionDetails', 'TransactionDetails.Product', 'TransactionDetails.Hampers')->where('customer_id', $id)->orderBy('id', 'desc')->get();

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
        $customer = Customers::with('Users')->find($transaction->customer_id);

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

        $threeDaysAfterBirthday = Carbon::parse($customer['users']['dateOfBirth'])->addDays(3);
        $threeDaysBeforeBirthday = Carbon::parse($customer['users']['dateOfBirth'])->subDays(3);

        if (Carbon::parse($transaction->order_date)->toDateString() >= $threeDaysBeforeBirthday && Carbon::parse($transaction->order_date)->toDateString() <= $threeDaysAfterBirthday) {
            $points = $points * 2;
        }
        $date = Carbon::parse($transaction->order_date);
        $dates = $date->format('y') . "." . $date->format('m') . "." . $transaction->id;

        return response([
            'message' => 'All data Retrievied',
            'data' => [
                'transaction' => $transaction,
                'details' => $detailOrders,
                'getPoint' => $points,
                'dates' => $dates
            ]
        ], 200);
    }

    public function storeBuyNow(Request $request)
    {
        $data = $request->all();
        $item = $data['data'];
        $productionDate = Carbon::parse($data['order_date'])->toDateString();
        $twoDayAfterNow = Carbon::now()->addDays(2)->toDateString();
        $now = Carbon::now()->subDay()->toDateString();

        if ($productionDate < $twoDayAfterNow && $item['status_item'] == 'Pre-Order') {
            return response([
                'message' => 'Minimum order H+2 from today',
            ], 400);
        }
        if ($productionDate < $now && $item['status_item'] == 'Ready') {
            return response([
                'message' => 'Cannot Order before today',
            ], 400);
        }
        $customer = Customers::where('user_id', auth()->user()->id)->first();
        if (!is_null($item['product_id'])) {
            $product = Product::where('id', $item['product_id'])->first();
        } else if (!is_null($item['hampers_id'])) {
            $hampers = Hampers::where('id', $item['hampers_id'])->first();
        }
        $transaction = Transactions::create([
            'order_date' => Carbon::now()->toDateTimeString(),
            'pickup_date' => $data['order_date'],
            'customer_id' => $customer->id,
            'status' => 'notPaid',
            'total_price' => $data['total']
        ]);

        if (!is_null($item['product_id'])) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'product_id' => $item['product_id'],
                'status_item' => $item['status_item'],
                'quantity' => $item['quantity'],
                'price' => $product->product_price,
                'total_price' => $item['total_price']
            ]);
        } else if (!is_null($item['hampers_id'])) {
            TransactionDetail::create([
                'transaction_id' => $transaction->id,
                'hampers_id' => $item['hampers_id'],
                'status_item' => $item['status_item'],
                'quantity' => $item['quantity'],
                'price' => $hampers->hampers_price,
                'total_price' => $item['total_price']
            ]);
        }

        return response([
            'message' => 'Transaction Added Successfully',
            'data' => [
                'transaction' => $transaction,
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $cart = Carts::where('order_date', Carbon::parse($data['order_date'])->toDateString())->get();
        $customer = Customers::where('user_id', auth()->user()->id)->first();


        $productionDate = Carbon::parse($data['order_date'])->toDateString();
        $twoDayAfterNow = Carbon::now()->addDays(2)->toDateString();
        $now = Carbon::now()->subDay()->toDateString();

        foreach ($data['data'] as $item) {
            if ($productionDate < $twoDayAfterNow && $item['status_item'] == 'Pre-Order') {
                return response([
                    'message' => 'Minimum order H+2 from today',
                ], 400);
            }
            if ($productionDate < $now && $item['status_item'] == 'Ready') {
                return response([
                    'message' => 'Cannot Order before today',
                ], 400);
            }
        }

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
        $customer = Customers::find($transaction->customer_id);
        $transaction->payment_method = $data['payment_method'];
        $transaction->paidoff_date = Carbon::now()->toDateTimeString();

        if ($data['payment_method'] == '"E-Money"') {
            // payment amount set to total price
            // change status to already paid (need confirmation
            $transaction->payment_amount = $data['total_price'];
            $transaction->status = 'alreadyPaid';

            // handle point logic
            $transaction->used_point = $data['point'];
            $transaction->earned_point = $data['point_earned'];
            $transaction->total_price = $data['total_price'];
            $transaction->current_point = $customer->point - $data['point'] + $data['point_earned'];

            // generate transaction number
            $date = Carbon::parse($transaction->order_date);
            $transaction->transaction_number = $date->format('y') . "." . $date->format('m') . "." . $transaction->id;

            // save transaction data
            $transaction->save();

            // handle customer point change
            // $customer->point = $customer->point - $data['point'] + $data['point_earned'];
            $customer->save();

            // handle product stock/quota change
            $details = TransactionDetail::where('transaction_id', $id)->get();
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
        } else { // handle cash payment method
            // point logic for cash method

            $productionDate = Carbon::parse($transaction->pickup_date)->toDateString();
            $twoDayAfterNow = Carbon::now()->addDays(2)->toDateString();
            $now = Carbon::now()->subDay()->toDateString();
            if ($productionDate < $twoDayAfterNow) {
                return response([
                    'message' => 'Your order was expired, please change the order date or delete your orders',
                ], 400);
            }
            if ($productionDate < $now) {
                return response([
                    'message' => 'Cannot Order before today',
                ], 400);
            }

            $transaction->used_point = $data['point'];
            $transaction->earned_point = $data['point_earned'];
            $transaction->total_price = $data['total_price'];
            $transaction->current_point = $customer->point - $data['point'] + $data['point_earned'];

            $date = Carbon::parse($transaction->order_date);
            $transaction->transaction_number = $date->format('y') . "." . $date->format('m') . "." . $transaction->id;

            $transaction->save();

            // $customer->point = $customer->point - $data['point'] + $data['point_earned'];

            $customer->save();
        }

        return response([
            'message' => 'Payment Successfull',
            'data' => $transaction
        ], 200);
    }

    public function storePaymentEvidence(Request $request, $id)
    {
        $transaction = Transactions::find($id);

        $uploadFolder = 'payment_evidence';
        $image = $request->file('payment_evidence');
        $imageUpladedPath = $image->store($uploadFolder, 'public');
        $uploadedImageResponse = basename($imageUpladedPath);

        $transaction->payment_evidence = $uploadedImageResponse;
        $transaction->save();

        return response([
            'message' => 'Payment Evidence Uploaded',
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

    public function updatePickUpDateTransaction(Request $request, $id)
    {
        $transaction = Transactions::find($id);
        $details = TransactionDetail::where('transaction_id', $id)->get();

        foreach ($details as $item) {
            if (!is_null($item->product_id)) {
                $product = Product::find($item->product_id);
                $limitProduct = ProductLimits::where('product_id', $item->product_id)->where('production_date', Carbon::parse($request->pickup_date)->toDateString())->first();
                if (!is_null($limitProduct)) {
                    if ($limitProduct->limit_amount < $item->quantity) {
                        return response([
                            'message' => "There are only " . $limitProduct->limit_amount . " " . $product->product_name . " products left."
                        ], 404);
                    }
                }
            } else if (!is_null($item->hampers_id)) {
                $hampers = Hampers::with('HampersDetail')->where('id', $item->hampers_id)->first();
                $hampersDetail = HampersDetails::where('hampers_id', $item->hampers_id)->where('ingredient_id', null)->get();
                foreach ($hampersDetail as $detail) {
                    $limitProduct = ProductLimits::where('product_id', $detail->product_id)->where('production_date', Carbon::parse($request->pickup_date)->toDateString())->first();
                    $product = Product::find($detail->product_id);
                    if (!is_null($limitProduct)) {
                        if ($limitProduct->limit_amount < $item->quantity) {
                            return response([
                                'message' => "In " . $hampers->hampers_name . " there are only " . $limitProduct->limit_amount . " " . $product->product_name . " products left."
                            ], 404);
                        }
                    }
                }
            }
        }

        $transaction->pickup_date = $request->pickup_date;
        $transaction->save();
        return response([
            'message' => 'Transaction Updated',
            'data' => $transaction
        ], 200);
    }

    public function deleteTransaction($id)
    {
        $transaction = Transactions::find($id);
        if (is_null($transaction)) {
            return response([
                'message' => 'Transaction Not Found',
            ], 404);
        }
        $transaction->delete();
        return response([
            'message' => 'Transaction Deleted',
            'data' => $transaction
        ], 200);
    }
}
