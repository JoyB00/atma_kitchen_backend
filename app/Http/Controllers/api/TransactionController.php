<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Carts;
use App\Models\Transactions;

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
        $transaction = Transactions::find(auth()->user()->id);
        $filtered = Carts::with('Product', 'Hampers')->where('transaction_id', $transaction->id)->whereHas('Product', function ($query) use ($term) {
            $query->where('name', 'like', '%' . $term . '%');
        })->get();

        return response([
            'message' => 'All data Retrievied',
            'data' => $filtered
        ], 200);
    }
}
