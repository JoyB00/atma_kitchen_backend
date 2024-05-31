<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\HistoryUseIngredients;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HistoryUseIngredientController extends Controller
{
    public function index()
    {
        $history = HistoryUseIngredients::with('Ingredient', 'Transaction')->get();
        return response([
            'message' => 'All Date Retrivied',
            'data' => $history
        ], 200);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'ingredient_id' => 'required',
            'transaction_id' => 'required',
            'quantity' => 'required|numeric',
            'date' => 'required|date'
        ]);
        if ($validate->fails()) {
            return response(['message' => $validate->errors()->first()], 400);
        }
        $store = HistoryUseIngredients::create($storeData);
        return response([
            'message' => 'Ingredient History Created',
            'data' => $store
        ], 200);
    }
}
