<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Carts;
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
        $detailOrders = Carts::with('Product', 'Hampers')->where('transaction_id', $id)->get();

        return response([
            'message' => 'All data Retrievied',
            'data' => [
                'transaction' => $transaction,
                'details' => $detailOrders
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
            $filtered = Carts::with('Product', 'Hampers')->where('transaction_id', $transaction[$i]->id)->whereHas('Product', function ($query) use ($term) {
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
