<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Carts;
use Illuminate\Http\Request;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use App\Models\Customers;

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

    public function store(Request $request)
    {
        $data = $request->all();
        $cart = Carts::where('order_date', $data['order_date'])->get();
        $transaction = Transactions::create([
            'order_date' => $data['order_date'],
            'customer_id' => auth()->user()->id,
            'status' => 'notPaid',
            'total_price' => $data['total']
        ]);
        foreach ($data['data'] as $item) {
            if (!is_null($item['product_id'])) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'product_id' => $item['product_id'],
                    'quantity' => $item['quantity'],
                    'price' => $item['products']['product_price'],
                    'total_price' => $item['total_price']
                ]);
            } else if (!is_null($item['hampers_id'])) {
                TransactionDetail::create([
                    'transaction_id' => $transaction->id,
                    'hampers_id' => $item['hampers_id'],
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
