<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HistoryUseIngredients;
use App\Models\Ingredients;
use App\Models\Transactions;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HistoryUseIngredientController extends Controller
{
    public function index()
    {
        $history = HistoryUseIngredients::with('Ingredients')->get();
        return response([
            'message' => 'All Date Retrivied',
            'data' => $history
        ], 200);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $recap = $storeData['recapIngredient'];
        $transaction = $storeData['transaction'];
        foreach ($transaction as $data) {
            $updateTrasaction = Transactions::find($data['id']);
            $updateTrasaction->status = 'onProcess';
            $updateTrasaction->save();
        }
        foreach ($recap as $item) {
            $ingredient = Ingredients::where('ingredient_name', $item['ingredient_name'])->first();
            $ingredient->quantity = $ingredient->quantity -  $item['quantity'];
            $ingredientUse = HistoryUseIngredients::where('ingredient_id', $ingredient->id)->where('date', Carbon::now()->toDateString())->first();
            if (is_null($ingredientUse)) {
                $store = HistoryUseIngredients::create([
                    'ingredient_id' => $ingredient->id,
                    'quantity' => $item['quantity'],
                    'date' => Carbon::now()->toDateString()
                ]);
            } else {
                $ingredientUse->quantity = $ingredientUse->quantity + $item['quantity'];
                $ingredientUse->save();
            }
            $ingredient->save();
        }
        return response([
            'message' => 'Ingredient History Created',
            'data' =>  $transaction
        ], 200);
    }
}
