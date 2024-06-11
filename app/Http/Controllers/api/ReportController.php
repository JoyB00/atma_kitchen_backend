<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Absence;
use App\Models\Consignors;
use App\Models\Employees;
use App\Models\TransactionDetail;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Carbon\Carbon;

class ReportController extends Controller
{
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
