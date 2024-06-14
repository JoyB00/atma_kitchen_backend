<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BalanceHistories;
use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class BalanceHistoriesController extends Controller
{
    public function index()
    {
        $authCustomer = Customers::where('user_id', auth()->user()->id);
        $balances = BalanceHistories::with('Customers', 'Customers.Users')->where('customer_id', $authCustomer->id)->get();

        return response([
            'message' => 'All Balance Histories Retrivied',
            'data' => $balances
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validate = Validator::make($data, [
            'balance_nominal' => 'required|numeric',
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
        $balanceHistory = BalanceHistories::create([
            'nominal_balance' => $data['balance_nominal'],
            'bank_name' => $data['bank_name'],
            'account_number' => $data['account_number'],
            'date' => $data['date'],
            'detail_information' => $data['detail_information'],
            'customer_id' => Customers::where('user_id', auth()->user()->id)->first()->id,
            'status' => 'Transaction Rejected'
        ]);
        return response([
            'message' => 'Balance History Created Successfully',
            'data' => $balanceHistory
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $balance_history = BalanceHistories::find($id);
        if (is_null($balance_history)) {
            return response([
                'message' => 'Balance History Not Found',
                'data' => null
            ], 404);
        }

        $data = $request->all();
        $validate = Validator::make($data, [
            'balance_nominal' => 'required|numeric',
            'bank_name' => 'required',
            'account_number' => 'required',
            'date' => 'required|date',
            'detail_information' => 'required'
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }

        $balance_history->update($data);
        return response([
            'message' => 'Balance History Updated Successfully',
            'data' => $balance_history
        ], 200);
    }

    public function destroy($id)
    {
        $balance_history = BalanceHistories::find($id);
        if (is_null($balance_history)) {
            return response([
                'message' => 'Balance History Not Found',
                'data' => null
            ], 404);
        }
        if ($balance_history->delete()) {
            return response([
                'message' => 'Balance History Deleted Successfully',
                'data' => $balance_history
            ], 200);
        }
        return response([
            'message' => 'Delete Balance History Failed',
            'data' => null,
        ], 400);
    }
}
