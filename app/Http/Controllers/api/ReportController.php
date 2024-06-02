<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function getProductSalesByMonth(Request $request)
    {
        $month = $request->input('month');

        $products = TransactionDetail::join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->leftJoin('products', 'transaction_details.product_id', '=', 'products.id')
            ->leftJoin('hampers', 'transaction_details.hampers_id', '=', 'hampers.id')
            ->selectRaw('COALESCE(products.product_name, hampers.hampers_name) as Product')
            ->selectRaw('SUM(transaction_details.quantity) as Quantity')
            ->select('transaction_details.price as Price')
            ->selectRaw('SUM(transaction_details.quantity * transaction_details.price) as total_harga')
            ->whereMonth('transactions.pickup_date', '=', $month)
            ->groupBy('Product', 'Price', 'Quantity')
            ->orderBy('Product')
            ->get();

        return response([
            'message' => 'All Data Retrieved',
            'data' => $products
        ], 200);
    }
}
