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
        $loggedInId = auth()->user()->id;
        $filteredDetailOrders = Carts::with('Product', 'Hampers')->whereHas('Product', function ($query) use ($term) {
            $query->where('product_name', 'like', '%' . $term . '%');
        })->orWhereHas('Hampers', function ($query) use ($term) {
            $query->where('hampers_name', 'like', '%' . $term . '%');
        })->whereHas('Transactions', function ($query) use ($loggedInId) {
            $query->where('customer_id', $loggedInId);
        })->get();

        return response([
            'message' => 'All data Retrievied',
            'data' => $filteredDetailOrders,
        ], 200);
    }
}
