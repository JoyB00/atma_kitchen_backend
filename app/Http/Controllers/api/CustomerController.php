<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        $customers = Customers::with('Users')->get();
        return response([
            'message' => 'All Customer Retrivied',
            'data' => $customers
        ], 200);
    }



    public function update(Request $request, $id)
    {
        $customer = Customers::find($id);
        // $user = User::find($customer->user_id);
        if (is_null($customer)) {
            return response([
                'message' => 'Customer Not Found'
            ], 404);
        }

        //TODO need testing
        $customer->update($request->all());
        return response([
            'message' => 'Customer Updated',
            'data' => $customer
        ], 200);
    }

    public function show($id)
    {
        $customer = Customers::find($id);
        if (is_null($customer)) {
            return response([
                'message' => 'Customer Not Found'
            ], 404);
        }
        return response([
            'message' => 'Customer with ID ' . $id . ' Retrieved',
            'data' => $customer
        ], 200);
    }
}
