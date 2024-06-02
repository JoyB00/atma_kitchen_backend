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
        $pickupDate = $request->input('pickup_date');

        $products = TransactionDetail::join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->leftJoin('products', 'transaction_details.product_id', '=', 'products.id')
            ->leftJoin('hampers', 'transaction_details.hampers_id', '=', 'hampers.id')
            ->select(
                DB::raw('COALESCE(products.product_name, hampers.hampers_name) as Product'),
                'transaction_details.quantity as Quantity',
                'transaction_details.price as Price'
            )
            ->whereMonth('transactions.pickup_date', '=',  $pickupDate)
            ->groupBy('Product', 'Price')
            ->orderBy('Product')
            ->get();

        return response([
            'message' => 'All Data Retrieved',
            'data' => $products
        ], 200);
    }
}
