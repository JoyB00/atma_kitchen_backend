<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BalanceHistories;
use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BalanceController extends Controller
{
    // Menampilkan saldo customer yang diotorisasi berdasarkan ID
    public function showBalance($id)
    {
        $customer = Customers::where('user_id', auth()->user()->id)->first();
        if (!$customer || $customer->id != $id) {
            return response([
                'message' => 'Customer not found or unauthorized'
            ], 404);
        }

        return response([
            'message' => 'Balance Retrieved Successfully',
            'data' => ['balance' => $customer->nominal_balance]
        ], 200);
    }

    // Menarik saldo customer yang diotorisasi berdasarkan ID
    public function withdrawBalance(Request $request, $id)
    {
        $customer = Customers::where('user_id', auth()->user()->id)->first();
        if (!$customer || $customer->id != $id) {
            return response([
                'message' => 'Customer not found or unauthorized'
            ], 404);
        }

        $data = $request->all();
        $validate = Validator::make($data, [
            'amount' => 'required|numeric|min:1',
            'bank_name' => 'required',
            'account_number' => 'required',
            'date' => 'required|date',
            'detail_information' => 'required'
        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first(),
            ], 400);
        }

        $amount = $data['amount'];
        if ($customer->nominal_balance < $amount) {
            return response([
                'message' => 'Insufficient balance'
            ], 400);
        }

        $customer->nominal_balance -= $amount;
        $customer->save();

        // Mencatat riwayat saldo
        BalanceHistories::create([
            'customer_id' => $customer->id,
            'balance_nominal' => -$amount,
            'bank_name' => $data['bank_name'],
            'account_number' => $data['account_number'],
            'date' => $data['date'],
            'detail_information' => $data['detail_information'],
        ]);

        return response([
            'message' => 'Withdrawal Successful',
            'data' => ['balance' => $customer->nominal_balance]
        ], 200);
    }

    // Menampilkan riwayat saldo customer yang diotorisasi berdasarkan ID
    public function balanceHistory($id)
    {
        $customer = Customers::where('user_id', auth()->user()->id)->first();
        if (!$customer || $customer->id != $id) {
            return response([
                'message' => 'Customer not found or unauthorized'
            ], 404);
        }

        $balanceHistories = BalanceHistories::where('customer_id', $customer->id)->get();

        return response([
            'message' => 'Balance histories retrieved successfully',
            'data' => $balanceHistories,
        ], 200);
    }
}
