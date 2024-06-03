<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class ReportController extends Controller
{
    public function getProductSalesByMonth(Request $request)
    {
        $month = $request->input('month');

        $products = TransactionDetail::join('transactions', 'transaction_details.transaction_id', '=', 'transactions.id')
            ->leftJoin('products', 'transaction_details.product_id', '=', 'products.id')
            ->leftJoin('hampers', 'transaction_details.hampers_id', '=', 'hampers.id')
            ->select(
                DB::raw('COALESCE(products.product_name, hampers.hampers_name) as Product'),
                'transaction_details.quantity as Quantity',
                'transaction_details.price as Price'
            )
            ->whereMonth('transactions.pickup_date', '=', $month)
            ->where('transactions.status', '=', 'finished')
            ->groupBy('Product', 'Price', 'Quantity')
            ->orderBy('Product')
            ->get();

        $total = 0;
        foreach ($products as $item) {
            $total = $total + ($item->Quantity * $item->Price);
        }

        return response([
            'message' => 'All Data Retrieved',
            'data' => [
                'product' => $products,
                'total' => $total
            ]
        ], 200);
    }

    public function salesReportMonthly(Request $request)
    {
        $month = [1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12];

        $data = $request->all();
        $validate = Validator::make(
            $data,
            [
                'year' => 'required'
            ]
        );
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first(),
            ], 400);
        }

        // generate report containing monthly sales count and total sales
        // get monthly transaction count on this month
        $monthlySalesCount = [];
        $monthlySalesTotal = [];
        for ($i = 1; $i <= 12; $i++) {
            $monthlySalesCount[$month[$i - 1]] = Transactions::whereYear('pickup_date', $data['year'])
                ->whereMonth('pickup_date', $i)
                ->where('status', 'finished')
                ->count();
        }
        //get total transaction on this month
        for ($i = 1; $i <= 12; $i++) {
            $monthlySalesTotal[$month[$i - 1]] = Transactions::whereYear('pickup_date', $data['year'])
                ->whereMonth('pickup_date', $i)
                ->where('status', 'finished')
                ->sum('total_price');
        }

        return response([
            'count' => $monthlySalesCount,
            'total' => $monthlySalesTotal,
        ], 200);
    }

}
