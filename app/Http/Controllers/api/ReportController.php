<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Consignors;
use App\Models\Employees;
use App\Models\HistoryUseIngredients;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use Carbon\Carbon;
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
                DB::raw('SUM(transaction_details.quantity) as TotalQuantity'),
                DB::raw('SUM(transaction_details.price * transaction_details.quantity) as TotalPrice')
            )
            ->whereMonth('transactions.pickup_date', '=', $month)
            ->where('transactions.status', '=', 'finished')
            ->groupBy('Product')
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
            $monthlySalesCount[$i - 1] = Transactions::whereYear('pickup_date', $data['year'])
                ->whereMonth('pickup_date', $i)
                ->where('status', 'finished')
                ->count();
        }
        //get total transaction on this month
        for ($i = 1; $i <= 12; $i++) {
            $monthlySalesTotal[$i - 1] = Transactions::whereYear('pickup_date', $data['year'])
                ->whereMonth('pickup_date', $i)
                ->where('status', 'finished')
                ->sum('total_price');
        }

        return response([
            'count' => $monthlySalesCount,
            'total' => $monthlySalesTotal,
        ], 200);
    }

    public function ingredientUsageReport(Request $request)
    {
        $data = $request->all();

        //make sure it's a valid date
        $validate = Validator::make(
            $data,
            [
                'from' => 'required',
                'to' => 'required'
            ]
        );

        if ($validate->fails() || strtotime($data['from']) > strtotime($data['to'])) {
            return response([
                'message' => "Invalid date range",
            ], 400);
        }

        // get ingredient usage report
        $ingredientUse = HistoryUseIngredients::with('Ingredients')
            ->whereBetween('date', [$data['from'], $data['to']])
            ->get();

        if ($ingredientUse->isEmpty()) {
            return response([
                'message' => 'No Data Found',
                'data' => []
            ], 200);
        }

        return response([
            'message' => 'All Data Retrieved',
            'data' => $ingredientUse
        ], 200);
    }

    public function getConsignorReport(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'year' => 'required|date_format:Y',
            'month' => 'required|date_format:n'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 400);
        }

        $year = $request->input('year');
        $month = $request->input('month');

        // Get the first and last date of the month
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();

        // Fetch the consignors and their sold products in the given month and year
        $consignors = Consignors::with('Product')->get();

        $report = [];

        foreach ($consignors as $consignor) {
            $consignorReport = [
                'consignor_name' => $consignor->consignor_name,
                'products' => [],
            ];

            foreach ($consignor->Product as $product) {
                // Fetch the transaction details for the given product and date range
                $soldDetails = TransactionDetail::where('product_id', $product->id)
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();


                $quantitySold = $soldDetails->sum('quantity');
                $totalPrice = $soldDetails->sum('total_price');
                $commission = $totalPrice * 0.2;
                $amountReceived = $totalPrice - $commission;

                $consignorReport['products'][] = [
                    'product_name' => $product->product_name,
                    'quantity_sold' => $quantitySold,
                    'sale_price' => $product->product_price,
                    'total' => $totalPrice,
                    'commission' => $commission,
                    'received' => $amountReceived,
                ];
            }

            $report[] = $consignorReport;
        }

        return response()->json($report);
    }

    public function getAbsenceReport(Request $request)
    {
        // Validate the request
        $validator = Validator::make($request->all(), [
            'year' => 'required|date_format:Y',
            'month' => 'required|date_format:n'
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors()], 400);
        }

        $year = $request->input('year');
        $month = $request->input('month');

        // Check if absence data exists for the requested month and year
        $absenceAvailable = Absence::whereMonth('absence_date', $month)
            ->whereYear('absence_date', $year)
            ->exists();

        if (!$absenceAvailable) {
            return response()->json(['message' => 'Absence data for the requested month and year is not available'], 404);
        }

        // Fetch employees with their absences and user details for the given month and year
        $employees = Employees::with(['Absence' => function ($query) use ($month, $year) {
            $query->whereYear('absence_date', $year)
                ->whereMonth('absence_date', $month);
        }, 'Users'])->get();

        $report = [];
        $totalWages = 0;

        foreach ($employees as $employee) {
            $absentCount = $employee->Absence->count();
            $presenceCount = Carbon::create($year, $month, 1)->daysInMonth - $absentCount;

            $dailyWage = $presenceCount * 100000;
            $bonus = ($absentCount <= 4) ? 500000 : 0;
            $totalWage = $dailyWage + $bonus;

            $report[] = [
                'nama' => $employee->Users ? $employee->Users->fullName : 'Unknown',
                'jumlah_hadir' => $presenceCount,
                'jumlah_bolos' => $absentCount,
                'honor_harian' => number_format($dailyWage, 0, ',', '.'),
                'bonus_rajin' => number_format($bonus, 0, ',', '.'),
                'total' => number_format($totalWage, 0, ',', '.'),
            ];

            $totalWages += $totalWage;
        }

        $totalWagesFormatted = number_format($totalWages, 0, ',', '.');

        return response()->json([
            'message' => 'Success Absence Report and Wage',
            'data' => $report,
            'total_wages' => $totalWagesFormatted
        ], 200);
    }
}
