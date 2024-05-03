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
        $detailOrders = Carts::with('Product', 'Hampers')->where('transaction_id', $id)->get();

        return response([
            'message' => 'All data Retrievied',
            'data' => $detailOrders,
        ], 200);
    }
}
