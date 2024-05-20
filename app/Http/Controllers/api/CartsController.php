<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Carts;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

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

    public function showCartPerDate()
    {
        $carts = Carts::with('Products', 'Hampers', 'User')
            ->where('user_id', auth()->user()->id)
            ->orderBy('order_date', 'desc')
            ->get();

        $groupedCarts = $carts->groupBy(function ($cart) {
            return $cart->order_date;
        });

        $formattedData = $groupedCarts->map(function ($items, $date) {
            return [
                'order_date' => $date,
                'data' => $items
            ];
        })->values();

        return response([
            'message' => 'All Carts Retrieved',
            'data' => $formattedData
        ], 200);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make(
            $storeData,
            [
                'quantity' => 'required|numeric|min:1',
                'order_date' => 'required|date'
            ],
        );
        $validate->after(function ($validator) use ($storeData) {
            $exists = DB::table('carts')
                ->where('product_id', $storeData['product_id'])
                ->where('order_date', $storeData['order_date'])
                ->exists();

            if ($exists) {
                $validator->errors()->add('product_id', 'The Product with this order date is already in your cart');
            }
        });
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }
        $productionDate = Carbon::parse($storeData['order_date']);
        $oneDayBeforeNow = Carbon::now()->addDay();
        $now = Carbon::now()->subDay();

        if ($productionDate < $oneDayBeforeNow && $storeData['status_item'] == 'Pre-Order') {
            return response([
                'message' => 'Minimum order H+2 from today',
            ], 400);
        }
        if ($productionDate < $now && $storeData['status_item'] == 'Ready') {
            return response([
                'message' => 'Cannot Order before today',
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
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }

        $cart->update($update);
        return response([
            'message' => 'Update Cart Item Successfully',
            'data' => $cart
        ], 200);
    }

    public function destroyListItem(Request $request)
    {
        $data = $request->all();
        $cart = Carts::where('order_date', $data['order_date'])->get();

        if (is_null($cart)) {
            return response([
                'message' => 'Cart List Not Found',
                'data' => $cart
            ], 404);
        }

        foreach ($cart as $item) {
            $item->delete();
        }

        return response([
            'message' => 'Cart List Deleted Successfully',
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
