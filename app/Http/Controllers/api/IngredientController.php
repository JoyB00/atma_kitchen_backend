<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Ingredients;
use Illuminate\Support\Facades\Validator;

class IngredientController extends Controller
{
    public function index()
    {
        $ingredient = Ingredients::where('active', 1)->orderBy('ingredient_name', 'asc')->get();
        return response([
            'message' => 'All Ingredient Retrivied',
            'data' => $ingredient
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validate = Validator::make($data, [
            'ingredient_name' => 'required',
            'quantity' => 'required',
            'unit' => 'required',
        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }

        $ingredient = Ingredients::create($data);
        return response([
            'message' => 'Ingredient Created Successfully',
            'data' => $ingredient,
        ], 200);
    }

    public function show($id)
    {
        $ingredient = Ingredients::find($id);
        if (is_null($ingredient)) {
            return response([
                'message' => 'Ingredient Not Found'
            ], 404);
        }
        return response([
            'message' => 'A Ingredient Retrieved',
            'data' => $ingredient
        ], 200);
    }

    public function getIngredient($id)
    {
        $ingredient = Ingredients::find($id);
        if (is_null($ingredient)) {
            return response([
                'message' => "Ingredient found"
            ], 404);
        }

        return response([
            'message' => 'Retrieve Ingredient Successfully',
            'data' => [
                'ingredient' => $ingredient,
            ]
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $ingredient = Ingredients::find($id);
        if (is_null($ingredient)) {
            return response([
                'message' => 'Ingredient Not Found'
            ]);
        }

        $data = $request->all();
        $validate = Validator::make($data, [
            'ingredient_name' => 'required',
            'quantity' => 'required',
            'unit' => 'required',
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }

        $ingredient->update($data);
        return response([
            'message' => 'Ingredient Updated Successfully',
            'data' => $ingredient
        ], 200);
    }

    public function destroy($id)
    {
        $ingredient = Ingredients::find($id);
        if (is_null($ingredient)) {
            return response([
                'message' => 'Ingredient Not Found'
            ]);
        }

        if ($ingredient->delete()) {
            return response([
                'message' => 'Ingredient Deleted Successfully',
                'data' => $ingredient
            ], 200);
        }

        return response([
            'message' => 'Delete Ingredient Failed',
            'data' => null,
        ], 400);
    }

    public function disableIngredient($id)
    {
        $ingredient = Ingredients::find($id);
        if (is_null($ingredient)) {
            return response([
                "message" => "Ingredient not found",
            ], 404);
        }
        $ingredient['active'] = 0;
        $ingredient->save();
        return response([
            "message" => "Ingredient disabled successfully",
            "data" => $ingredient
        ], 200);
    }
}
