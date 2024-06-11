<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Consignors;
use App\Models\Employees;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use App\Models\OtherProcurements;
use App\Models\HistoryUseIngredients;
use App\Models\IngredientProcurements;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
                DB::raw('SUM(transaction_details.quantity) as Quantity'),
                DB::raw('SUM(transaction_details.price * transaction_details.quantity) as Price'),
                DB::raw('IFNULL(products.product_price, hampers.hampers_price) as OriginalPrice')
            )
            ->whereMonth('transactions.pickup_date', '=', $month)
            ->where('transactions.status', '=', 'finished')
            ->groupBy('Product', 'OriginalPrice')
            ->orderBy('Product')
            ->get();




        $total = 0;
        foreach ($products as $item) {
            $total = $total + ($item->Quantity * $item->OriginalPrice);
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
    
        // Log validated inputs
        Log::info('Validated inputs:', ['year' => $year, 'month' => $month]);
    
        // Get the first and last date of the month
        $startDate = Carbon::createFromDate($year, $month, 1)->startOfMonth();
        $endDate = Carbon::createFromDate($year, $month, 1)->endOfMonth();
    
        // Log the date range
        Log::info('Date Range:', ['startDate' => $startDate, 'endDate' => $endDate]);
    
        // Fetch the consignors and their sold products in the given month and year
        $consignors = Consignors::with(['Product' => function($query) use ($startDate, $endDate) {
            $query->whereHas('TransactionDetail', function($query) use ($startDate, $endDate) {
                $query->whereBetween('created_at', [$startDate, $endDate]);
            });
        }])->get();
    
        // Log fetched consignors
        Log::info('Fetched Consignors:', ['consignors' => $consignors->toArray()]);
    
        $report = [];
    
        foreach ($consignors as $consignor) {
            Log::info('Processing Consignor:', ['consignor_name' => $consignor->consignor_name]);
    
            $consignorReport = [
                'consignor_name' => $consignor->consignor_name,
                'products' => [],
            ];
    
            foreach ($consignor->Product as $product) {
                Log::info('Processing Product:', ['product_name' => $product->product_name]);
    
                // Fetch the transaction details for the given product and date range
                $soldDetails = $product->TransactionDetail()
                    ->whereBetween('created_at', [$startDate, $endDate])
                    ->get();
    
                $quantitySold = $soldDetails->sum('quantity');
                $totalPrice = $soldDetails->sum('total_price');
                $commission = $totalPrice * 0.2;
                $amountReceived = $totalPrice - $commission;
    
                // Log each product's sold details
                Log::info('Product Sold Details:', [
                    'product_name' => $product->product_name,
                    'quantity_sold' => $quantitySold,
                    'totalPrice' => $totalPrice,
                    'commission' => $commission,
                    'amountReceived' => $amountReceived,
                ]);
    
                // Only include the product if it has been sold in the date range
                if ($quantitySold > 0) {
                    $consignorReport['products'][] = [
                        'product_name' => $product->product_name,
                        'quantity_sold' => $quantitySold,
                        'sale_price' => $product->product_price,
                        'total' => $totalPrice,
                        'commission' => $commission,
                        'received' => $amountReceived,
                    ];
                }
            }
    
            // Only include consignors with at least one sold product
            if (!empty($consignorReport['products'])) {
                $report[] = $consignorReport;
            }
        }
    
        // Log final report
        Log::info('Final Report:', ['report' => $report]);
    
        return response()->json($report);
    }
    

    public function getAbsenceReport(Request $request)
{
    // Validasi permintaan
    $validator = Validator::make($request->all(), [
        'year' => 'required|date_format:Y',
        'month' => 'required|date_format:n'
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => $validator->errors()], 400);
    }

    $year = $request->input('year');
    $month = $request->input('month');

    // Periksa apakah data ketidakhadiran tersedia untuk bulan dan tahun yang diminta
    $absenceAvailable = Absence::whereMonth('absence_date', $month)
                                ->whereYear('absence_date', $year)
                                ->exists();
    if (!$absenceAvailable) {
        return response()->json(['message' => 'Absence data for the requested month and year is not available'], 404);
    }

    // Ambil data karyawan dengan absensinya dan detail pengguna untuk bulan dan tahun yang diberikan
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
            'employee_name' => $employee->Users ? $employee->Users->fullName : 'Unknown',
            'total_attendance' => $presenceCount,
            'total_absent' => $absentCount,
            'daily_wages' => $dailyWage,
            'bonus' => $bonus,
            'total_wages' => $totalWage,
        ];

        $totalWages += $totalWage;
    }

    Log::info('Absence Report Data:', ['data' => $report, 'total_wages' => $totalWages]);

    return response()->json([
        'message' => 'Success Absence Report and Wage',
        'data' => $report,
        'total_wages' => $totalWages
    ], 200);
}




public function getIncomeAndExpenseReport(Request $request)
{
    // Validasi input bulan dan tahun
    $validator = Validator::make($request->all(), [
        'year' => 'required|date_format:Y',
        'month' => 'required|date_format:n'
    ]);

    if ($validator->fails()) {
        return response()->json(['message' => $validator->errors()], 400);
    }

    $year = $request->input('year');
    $month = $request->input('month');

    // Ambil data pendapatan
    $totalSales = TransactionDetail::whereHas('transaction', function ($query) use ($year, $month) {
        $query->whereYear('pickup_date', $year)
              ->whereMonth('pickup_date', $month);
    })->sum('total_price');

    $totalTip = floatval(Transactions::whereYear('pickup_date', $year)
                            ->whereMonth('pickup_date', $month)
                            ->sum('tip'));

    $totalIncome = $totalSales + $totalTip;

    // Ambil data pengeluaran dari pembelian lain
    $expenseList = OtherProcurements::whereYear('procurement_date', $year)
                                    ->whereMonth('procurement_date', $month)
                                    ->get();

    $ingredientExpenses = IngredientProcurements::whereYear('procurement_date', $year)
                                                ->whereMonth('procurement_date', $month)
                                                ->get();

    $expenseSummary = [];
    $totalExpense = 0;

    foreach ($expenseList as $expense) {
        $expenseName = $expense->item_name;
        $totalExpenseItem = $expense->total_price;

        if (!isset($expenseSummary[$expenseName])) {
            $expenseSummary[$expenseName] = 0;
        }

        $expenseSummary[$expenseName] += $totalExpenseItem;
        $totalExpense += $totalExpenseItem;
    }

    foreach ($ingredientExpenses as $expense) {
        $expenseName = 'Raw Material'; // Mengasumsikan nama khusus untuk pengeluaran bahan baku
        $totalExpenseItem = $expense->total_price;

        if (!isset($expenseSummary[$expenseName])) {
            $expenseSummary[$expenseName] = 0;
        }

        $expenseSummary[$expenseName] += $totalExpenseItem;
        $totalExpense += $totalExpenseItem;
    }

    $incomeData = [
        'Sales' => $totalSales,
        'Tip' => $totalTip
    ];

    $expenseData = [];
    foreach ($expenseSummary as $name => $total) {
        $expenseData[$name] = $total;
    }

    return response()->json([
        'message' => 'Income and Expense Report',
        'data' => [
            'Income' => $incomeData,
            'Expenses' => $expenseData,
            'Total Income' => $totalIncome,
            'Total Expenses' => $totalExpense
        ]
    ], 200);
}


}

