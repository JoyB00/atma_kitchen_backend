<?php

namespace App\Http\Controllers;

use App\Models\Recipes;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RecipeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $recipe = Recipes::get();
        return response([
            'message' => 'All Formula Retrieved',
            'data' => $recipe
        ], 200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->all();
        $validate = Validator::make($data, [
            'quantity' => 'required',
            'id_bahan_baku' => 'required',
            'id_produk' => 'required',
        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }

        $recipe = Recipes::create($data);
        return response([
            'message' => 'Formula Created Successfully',
            'data' => $recipe,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $recipe = Recipes::where('id', $id)->get();
        $product = $recipe::with('Product')->get();
        $ingredient = $recipe::with('Ingredients')->get();
        return response([
            'message' => 'All Formula Retrieved',
            'data' => [
                'formula' => $recipe,
                'product' => $product,
                'ingredient' => $ingredient
            ],
        ], 200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $recipe = Recipes::find($id);

        if (is_null($recipe)) {
            return response([
                'message' => 'Formula Not Found',
                'data' => null
            ], 404);
        }


        $data = $request->all();
        $validate = Validator::make($data, [
            'quantity' => 'required',
            'id_bahan_baku' => 'required',
            'id_produk' => 'required',
        ]);

        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }

        $recipe->update($data);
        return response([
            'message' => 'Formula Updated Successfully',
            'data' => $recipe
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipes $recipe)
    {
        $recipe->delete();
        return response([
            'message' => 'Formula Deleted Successfully',
            'data' => $recipe
        ], 200);
    }
}
