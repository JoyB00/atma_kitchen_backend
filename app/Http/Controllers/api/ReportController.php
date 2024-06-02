<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReportController extends Controller
{
    public function getProductSalesByMonth(Request $request)
    {
        $pickupDate = $request->input('pickup_date');

        $products = DB::table('transactions')
            ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
            ->join('products', 'transaction_details.product_id', '=', 'products.id')
            ->select('products.product_name as Product', 'transaction_details.quantity as Quantity', 'transaction_details.price as Price')
            ->whereMonth('transactions.pickup_date', '=', $pickupDate)
            ->unionAll(
                DB::table('transactions')
                    ->join('transaction_details', 'transactions.id', '=', 'transaction_details.transaction_id')
                    ->join('hampers', 'transaction_details.hampers_id', '=', 'hampers.id')
                    ->select('hampers.hampers_name as Product', 'transaction_details.quantity as Quantity', 'transaction_details.price as Price')
                    ->whereMonth('transactions.pickup_date', '=', $pickupDate)
            )
            ->groupBy('Product', 'Price')
            ->orderBy('Product')
            ->get();

        return response([
            'message' => 'All Data Retrieved',
            'data' => $products
        ], 200);
    }
}
