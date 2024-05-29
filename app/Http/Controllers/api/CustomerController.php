<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Customers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CustomerController extends Controller
{
    public function index()
    {
        $customer = Customers::with('Users')->get();
        return response([
            'message' => 'Retrieve All Customers Successfully',
            'data' => $customer,
        ], 200);
    }

    public function show($id)
    {
        $customer = Customers::with('Users')->find($id);
        if (is_null($customer)) {
            return response([
                'message' => 'Customer Not Found'
            ], 404);
        }
        return response([
            'message' => 'Customer Retrieved Successfully',
            'data' => $customer
        ], 200);
    }

    public function showLoggedIn()
    {
        $customer = Customers::with('Users')->where('user_id', auth()->user()->id)->first();
        if (is_null($customer)) {
            return response([
                'message' => 'Customer Not Found'
            ], 404);
        }
        return response([
            'message' => 'Customer Retrieved Successfully',
            'data' => $customer
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $customer = Customers::with('Users')->find($id);
        if (is_null($customer)) {
            return response([
                'message' => 'Customer Not Found'
            ], 404);
        }
        $validate = Validator::make($request->all(), [
            'fullName' => 'required',
            'gender' => 'required',
            'phoneNumber' => 'required|max:13|min:10',
            'dateOfBirth' => 'required',
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }
        $request['password'] = bcrypt($request->password);

        $customer->users->update($request->all());
        return response([
            'message' => 'Customer Updated',
            'data' => $customer
        ], 200);
    }
}
