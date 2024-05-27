<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Models\Carts;
use App\Models\Customers;
use App\Models\Hampers;
use App\Models\Product;
use App\Models\ProductLimits;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

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
            if (!is_null($storeData['product_id'])) {
                $exists = DB::table('carts')
                    ->where('product_id', $storeData['product_id'])
                    ->where('order_date', $storeData['order_date'])
                    ->exists();
                if ($exists) {
                    $validator->errors()->add('product_id', 'The Product with this order date is already in your cart');
                }
            } else if (!is_null($storeData['hampers_id'])) {
                $exists = DB::table('carts')
                    ->where('hampers_id', $storeData['hampers_id'])
                    ->where('order_date', $storeData['order_date'])
                    ->exists();
                if ($exists) {
                    $validator->errors()->add('hampers_id', 'The Hampers with this order date is already in your cart');
                }
            }
        });
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }
        $productionDate = Carbon::parse($storeData['order_date'])->toDateString();
        $twoDayAfterNow = Carbon::now()->addDays(2)->toDateString();
        $now = Carbon::now()->subDay()->toDateString();

        if ($productionDate < $twoDayAfterNow && $storeData['status_item'] == 'Pre-Order') {
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
    public function updateListCart(Request $request)
    {
        $updateData = $request->all();

        $carts = Carts::where('order_date', $updateData['late_order_date'])->where('user_id', auth()->user()->id)->get();

        $productionDate = Carbon::parse($updateData['order_date'])->toDateString();
        $twoDayAfterNow = Carbon::now()->addDays(2)->toDateString();
        $now = Carbon::now()->subDay()->toDateString();
        if ($productionDate < $twoDayAfterNow) {
            return response([
                'message' => 'Minimum order H+2 from today',
            ], 400);
        }
        if ($productionDate < $now) {
            return response([
                'message' => 'Cannot Order before today',
            ], 400);
        }

        foreach ($carts as $item) {
            if (!is_null($item->product_id)) {
                $product = Product::find($item->product_id);
                $limitProduct = ProductLimits::where('product_id', $item->product_id)->where('production_date', Carbon::parse($updateData['order_date'])->toDateString())->first();
                if (!is_null($limitProduct)) {
                    if ($limitProduct->limit_amount < $item->quantity) {
                        return response([
                            'message' => "There are only " . $limitProduct->limit_amount . " " . $product->product_name . " products left."
                        ], 404);
                    }
                }
            } else if (!is_null($item->hampers_id)) {
                $hampers = Hampers::with('HampersDetail')->where('id', $item->hampers_id)->first();
                foreach ($hampers['hampers_detail'] as $detail) {
                    $limitProduct = ProductLimits::where('product_id', $detail->product_id)->where('production_date', Carbon::parse($updateData['order_date'])->toDateString())->first();
                    if (!is_null($limitProduct)) {
                        if ($limitProduct->quantity < $item->quantity) {
                            return response([
                                'message' => "In " . $hampers->hampers_name . " there are only " . $limitProduct->limit_amount . " " . $detail->product_name . " products left."
                            ], 404);
                        }
                    }
                }
            }
        }

        foreach ($carts as $item) {
            $item->update($updateData);
        }


        return response([
            'message' => 'Update Cart Item Successfully',
            'data' => $carts
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
