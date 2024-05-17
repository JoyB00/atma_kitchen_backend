<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Carts;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CartsController extends Controller
{
    public function index()
    {
        $data = Carts::with('Products', 'Hampers', 'User')->where('user_id', auth()->user()->id)->orderBy('order_date', 'desc')->get();
        return response([
            'message' => 'All Carts Retrivied',
            'data' => $data
        ], 200);
    }


    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'quantity' => 'required|numeric|min:1',
            'order_date' => 'required|date'
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }
        $productionDate = Carbon::parse($storeData['order_date']);
        $oneDayBeforeNow = Carbon::now()->subDay();

        if ($productionDate < $oneDayBeforeNow) {
            return response([
                'message' => 'Order date cannot be before today',
            ], 400);
        }

        $storeData['user_id'] = auth()->user()->id;
        $cart = Carts::create($storeData);
        return response([
            'message' => 'Add To Cart Successfully',
            'data' => $cart
        ], 200);
    }
    public function update(Request $request, $id)
    {
        $cart = Carts::find($id);
        if (is_null($cart)) {
            return response([
                'message' => 'Cart Not Found'
            ], 404);
        }
        $update = $request->all();
        $validate = Validator::make($update, [
            'quantity' => 'required|numeric|min:1',
            'order_date' => 'required|date'
        ]);
        $productionDate = Carbon::parse($update['order_date']);
        $oneDayBeforeNow = Carbon::now()->subDay();
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        } else if ($productionDate < $oneDayBeforeNow) {
            return response([
                'message' => 'Order date cannot be before today',
            ], 400);
        }

        $cart->update($update);
        return response([
            'message' => 'Update Cart Item Successfully',
            'data' => $cart
        ], 200);
    }

    public function destroy($id)
    {
        $cart = Carts::find($id);
        if (is_null($cart)) {
            return response([
                'message' => 'Cart Not Found'
            ], 404);
        }
        if ($cart->delete()) {
            return response([
                'message' => 'Cart Item Deleted Successfully',
                'data' => $cart
            ], 200);
        }
        return  response([
            'message' => 'Delete Cart Item Failed',
            'data' => null,
        ], 400);
    }
}