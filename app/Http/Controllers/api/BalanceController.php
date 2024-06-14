<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BalanceHistories;
use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
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
            'detail_information' => 'required'
        ]);
        $data['date'] = now();

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

        // Log the withdrawal request creation
        Log::info('Creating withdrawal request', [
            'customer_id' => $customer->id,
            'withdraw_amount' => $amount,
        ]);

        // Create a withdrawal request with status pending
        $balanceHistory = BalanceHistories::create([
            'customer_id' => $customer->id,
            'nominal_balance' => -$amount,
            'bank_name' => $data['bank_name'],
            'account_number' => $data['account_number'],
            'date' => $data['date'],
            'detail_information' => $data['detail_information'],
            'status' => 'Awaiting Confirmation' // Add status 'Awaiting Confirmation'
        ]);

        Log::info('Withdrawal request created with status:', ['id' => $balanceHistory->id, 'status' => $balanceHistory->status]);

        return response([
            'message' => 'Withdrawal request submitted successfully, awaiting confirmation',
        ], 200);
    }


// Menampilkan riwayat saldo customer yang diotorisasi berdasarkan ID
    public function withdrawHistory($id)
    {
        $customer = Customers::where('user_id', auth()->user()->id)->first();
        if (!$customer || $customer->id != $id) {
            return response([
                'message' => 'Customer not found or unauthorized'
            ], 404);
        }

        $balanceHistories = BalanceHistories::where('customer_id', $customer->id)->get();

        // Ensure the status is included in the response
        $balanceHistoriesWithStatus = $balanceHistories->map(function ($history) {
            return [
                'id' => $history->id,
                'customer_id' => $history->customer_id,
                'nominal_balance' => $history->nominal_balance,
                'bank_name' => $history->bank_name,
                'account_number' => $history->account_number,
                'date' => $history->date,
                'detail_information' => $history->detail_information,
                'created_at' => $history->created_at,
                'updated_at' => $history->updated_at,
                'status' => $history->status ?? 'Awaiting Confirmation' // Set default status if null
            ];
        });

        return response([
            'message' => 'Balance histories retrieved successfully',
            'data' => $balanceHistoriesWithStatus,
        ], 200);
    }

// Menampilkan pengajuan penarikan saldo (Admin)
    public function showWithdrawalRequests()
    {
        // Check if the authenticated user is an admin
        if (auth()->user()->role_id != 2) {
            return response([
                'message' => 'Unauthorized'
            ], 403);
        }

        $withdrawalRequests = BalanceHistories::where('status', 'Awaiting Confirmation')->get();

        Log::info('Withdrawal Requests:', $withdrawalRequests->toArray());

        if ($withdrawalRequests->isEmpty()) {
            return response([
                'message' => 'No withdrawal requests found',
                'data' => []
            ], 200);
        }

        return response([
            'message' => 'Withdrawal requests retrieved successfully',
            'data' => $withdrawalRequests,
        ], 200);
    }


// Mengkonfirmasi pengajuan penarikan saldo (Admin)
    public function confirmWithdrawal(Request $request, $id)
    {
        if (auth()->user()->role_id != 2) {
            return response([
                'message' => 'Unauthorized'
            ], 403);
        }

        // Find the withdrawal request by ID and ensure its status is pending
        $withdrawalRequest = BalanceHistories::where('id', $id)->where('status', 'Awaiting Confirmation')->first();

        if (!$withdrawalRequest) {
            return response([
                'message' => 'Withdrawal request not found or already processed'
            ], 404);
        }

        Log::info('Found withdrawal request ID: ' . $id . ' with status: ' . $withdrawalRequest->status);

        // Find the customer associated with the withdrawal request
        $customer = Customers::find($withdrawalRequest->customer_id);
        if (!$customer) {
            Log::warning('Customer not found for customer ID: ' . $withdrawalRequest->customer_id);
            return response([
                'message' => 'Customer not found'
            ], 404);
        }

        DB::beginTransaction();
        try {
            // Deduct the balance from the customer's account
            $customer->nominal_balance += $withdrawalRequest->nominal_balance;
            $customer->save();

            // Update the status of the withdrawal request to confirmed
            $withdrawalRequest->status = 'Confirmed';
            $withdrawalRequest->save();

            Log::info('Withdrawal request confirmed for request ID: ' . $id . ', new customer balance: ' . $customer->nominal_balance);

            DB::commit();

            return response([
                'message' => 'Withdrawal request confirmed successfully',
            ], 200);
        } catch (\Exception $e) {
            DB::rollBack();
            Log::error('An error occurred while confirming the withdrawal request: ' . $e->getMessage());
            return response([
                'message' => 'An error occurred while confirming the withdrawal request'
            ], 500);
        }
    }


}
