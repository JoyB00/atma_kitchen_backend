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
        $transactions = Transactions::whereHas('carts', function ($query) use ($term) {
            $query->whereHas('product', function ($query) use ($term) {
                $query->where('product_name', 'like', '%' . $term . '%');
            });
        })->get();

        return response([
            'message' => 'All data Retrievied',
            'data' => $transactions,
        ], 200);
    }
}
