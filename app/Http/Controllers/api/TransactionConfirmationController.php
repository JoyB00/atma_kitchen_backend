<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\BalanceHistories;
use App\Models\Customers;
use App\Models\Deliveries;
use App\Models\HampersDetails;
use App\Models\Ingredients;
use App\Models\Product;
use App\Models\ProductLimits;
use App\Models\TransactionDetail;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class TransactionConfirmationController extends Controller
{
    public function MoConfirmation(Request $request)
    {
        $data = $request->all();

        $transaction = Transactions::find($data['id']);
        $customer = Customers::find($transaction->customer_id);
        if ($data['status'] == 'accepted') {
            $transaction->status = 'accepted';
            $customer->point = $customer->point -  $transaction->used_point + $transaction->earned_point;
            $transaction->save();
            $customer->save();
        } else {
            // handle product stock/quota change
            $details = TransactionDetail::where('transaction_id', $data['id'])->get();
            foreach ($details as $item) {
                // if product then ... else hampers
                if (!is_null($item->product_id)) {
                    $product = Product::find($item->product_id);

                    // if product status is ready then reduce ready stock, if not then add product limit(?)
                    if ($item->status_item == 'Ready') {
                        $product->ready_stock = $product->ready_stock + $item->quantity; // reduce ready stock (if status is ready)
                    } else {
                        $limit = ProductLimits::where('production_date', Carbon::parse($transaction->pickup_date)->toDateString())->where('product_id', $item->product_id)->first();
                        $limit->update([
                            'limit_amount' => $limit->limit_amount + $item->quantity,
                        ]);
                    }
                    $product->save();
                } else if (!is_null($item->hampers_id)) {
                    $detailHampers = HampersDetails::where('hampers_id', $item->hampers_id)->get();
                    foreach ($detailHampers as $dh) {
                        if (!is_null($dh->product_id)) {
                            $p = Product::find($dh->product_id);
                            if ($item->status_item == 'Ready') {
                                $p->ready_stock = $p->ready_stock + $item->quantity;
                            } else {
                                $limit = ProductLimits::where('production_date', Carbon::parse($transaction->pickup_date)->toDateString())->where('product_id', $p->id)->first();
                                $limit->update([
                                    'limit_amount' => $limit->limit_amount + $item->quantity,
                                ]);
                            }
                            $p->save();
                        } else if (!is_null($dh->ingredient_id)) {
                            $i = Ingredients::find($dh->ingredient_id);
                            $i->update([
                                'quantity' => $i->quantity + 1
                            ]);
                        }
                    }
                }
            }
            $transaction->status = 'rejected';
            // $customer->point = $customer->point -  $transaction->used_point - $transaction->earned_point;
            $subtotal = 0;
            foreach ($details as $item) {
                $subtotal = $subtotal + $item->total_price;
            }
            $delivery = Deliveries::find($transaction->delivery_id);
            if (!is_null($delivery->shipping_cost)) {
                $customer->nominal_balance =  $customer->nominal_balance + $subtotal + $delivery->shipping_cost;
            } else {
                $customer->nominal_balance =  $customer->nominal_balance + $subtotal;
            }
            $transaction->save();
            $customer->save();
            BalanceHistories::create([
                'customer_id' => $customer->id,
                'nominal_balance' => $transaction->total_price,
                'bank_name' => 'BRI',
                'account_number' => '8769123441231',
                'date' => Carbon::now()->toDateString(),
                'detail_information' => 'Refund from online purchase'
            ]);
        }

        return response([
            'message' => 'Transaction Updated',
            'data' => $data
        ], 200);
    }

    public function usedIngredient(array $transactionIds)
    {
        // Mapping products with special quantity requirements to their full-size equivalents
        $specialProducts = [
            '1/2 loyang' => '1 loyang'
        ];

        // Fetch full-size product ids for special products
        $fullSizeProductIds = DB::table('products')
            ->whereIn('product_name', array_values($specialProducts))
            ->pluck('id', 'product_name')
            ->toArray();

        $subquery1 = Transactions::join('transaction_details as dt', 'transactions.id', '=', 'dt.transaction_id')
            ->join('products as p', 'dt.product_id', '=', 'p.id')
            ->join('recipes as r', 'r.product_id', '=', 'p.id')
            ->join('ingredients as i', 'i.id', '=', 'r.ingredient_id')
            ->whereIn('transactions.id', $transactionIds)
            ->groupBy('i.ingredient_name')
            ->select('i.ingredient_name')
            ->selectRaw('CAST(SUM(r.quantity * dt.quantity) AS DECIMAL) as quantity')
            ->get();

        $subquery2 = Transactions::join('transaction_details as dt', 'transactions.id', '=', 'dt.transaction_id')
            ->join('hampers as h', 'h.id', '=', 'dt.hampers_id')
            ->join('hampers_details as hd', 'h.id', '=', 'hd.hampers_id')
            ->join('products as p', 'hd.product_id', '=', 'p.id')
            ->join('recipes as r', 'r.product_id', '=', 'p.id')
            ->join('ingredients as i', 'i.id', '=', 'r.ingredient_id')
            ->whereIn('transactions.id', $transactionIds)
            ->groupBy('i.ingredient_name')
            ->select('i.ingredient_name')
            ->selectRaw('CAST(SUM(r.quantity * dt.quantity) AS DECIMAL) as quantity')
            ->get();

        $subquery3 = Transactions::join('transaction_details as dt', 'transactions.id', '=', 'dt.transaction_id')
            ->join('hampers as h', 'h.id', '=', 'dt.hampers_id')
            ->join('hampers_details as hd', 'h.id', '=', 'hd.hampers_id')
            ->leftJoin('products as p', 'hd.product_id', '=', 'p.id')
            ->join('ingredients as i', 'i.id', '=', 'hd.ingredient_id')
            ->whereIn('transactions.id', $transactionIds)
            ->groupBy('i.ingredient_name')
            ->select('i.ingredient_name')
            ->selectRaw('CAST(COUNT(p.product_name) AS DECIMAL) as quantity')
            ->get();

        // Merging and summing up quantities from all subqueries
        $results = collect($subquery1)
            ->merge($subquery2)
            ->merge($subquery3)
            ->groupBy('ingredient_name')
            ->map(function ($group) use ($specialProducts, $fullSizeProductIds) {
                $ingredientName = $group->first()->ingredient_name;
                $quantity = $group->sum('quantity');

                // Adjust quantity if product has a special mapping and quantity is 1
                foreach ($group as $item) {
                    if (isset($specialProducts[$item->product_name]) && $item->quantity == 1) {
                        $fullSizeProductName = $specialProducts[$item->product_name];
                        // Check if we have the full-size product's recipe quantity
                        if (isset($fullSizeProductIds[$fullSizeProductName])) {
                            // Fetch the recipe for the full-size product
                            $fullSizeRecipe = DB::table('recipes')
                                ->where('product_id', $fullSizeProductIds[$fullSizeProductName])
                                ->where('ingredient_id', function ($query) use ($ingredientName) {
                                    $query->select('id')
                                        ->from('ingredients')
                                        ->where('ingredient_name', $ingredientName);
                                })
                                ->first();
                            if ($fullSizeRecipe) {
                                $quantity += $fullSizeRecipe->quantity;
                            }
                        }
                    }
                }

                return [
                    'ingredient_name' => $ingredientName,
                    'quantity' => $quantity
                ];
            })
            ->values()
            ->sortBy('ingredient_name')
            ->values();

        return $results;
    }



    public function recapUsedIngredient(Request $request)
    {
        $data = $request->all();
        $transaksiArray = [];
        $transaksiID_array = [];

        foreach ($data['item'] as $key => $i) {
            $temp = Transactions::with(
                'Customer',
                'Customer.Users',
                'TransactionDetails',
                'TransactionDetails.Product',
                'TransactionDetails.Hampers',
                'TransactionDetails.Hampers.HampersDetail',
                'TransactionDetails.Hampers.HampersDetail.Product',
                'TransactionDetails.Product.AllRecipes',
                'TransactionDetails.Product.AllRecipes.Ingredients'
            )->find($i['id']);
            $transaksiArray[$key] = $temp;
            $transaksiID_array[$key] = $temp->id;
        }

        $recapIngredient = $this->usedIngredient($transaksiID_array);


        return response([
            'message' => 'All Recap Retrivied',
            'data' => [
                'transaction' => $transaksiArray,
                'recapIngredient' => $recapIngredient
            ]
        ], 200);
    }

    public function showShortageIngredient($id)
    {
        $transactionId = $id;
        $results = $this->usedIngredient($transactionId);

        $shortageIngredient = $results->map(function ($item) {
            $ingredient = Ingredients::where('ingredient_name', $item['ingredient_name'])->first();
            if ($ingredient->quantity < $item['quantity']) {
                return [
                    'ingredient_name' => $item['ingredient_name'],
                    'quantity' => $item['quantity'] - $ingredient->quantity
                ];
            }
        });

        // Filter out null values from the collection
        $shortageIngredient = $shortageIngredient->filter(function ($item) {
            return !is_null($item);
        });

        // If you need to reindex the collection (optional)
        $shortageIngredient = $shortageIngredient->values();

        return response([
            'message' => 'Show all ingredient used',
            'data' => $results
        ], 200);
    }



    public function showTransactionNeedToProccess()
    {
        $tommorow = Carbon::now()->addDay()->toDateString();
        $transaction = Transactions::with('Delivery', 'Customer', 'Customer.users', 'TransactionDetails', 'TransactionDetails.Product', 'TransactionDetails.Hampers')->where('status', 'accepted')->whereDate('pickup_date', $tommorow)->get();
        if (is_null($transaction)) {
            return response([
                'message' => 'No transaction need to proccess',
                'data' => null
            ], 404);
        }

        return response([
            'message' => 'Show all transaction need to proccess',
            'data' => $transaction
        ], 200);
    }
}
