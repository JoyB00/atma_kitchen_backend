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
        $formula = Recipes::get();
        return response([
            'message' => 'All Formula Retrieved',
            'data' => $formula,
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

        $formula = Recipes::create($data);
        return response([
            'message' => 'Formula Created Successfully',
            'data' => $formula,
        ], 200);
    }

    /**
     * Display the specified resource.
     */
    // public function show(Formula $formula)
    // {
    //     //
    // }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $formula = Recipes::find($id);

        if (is_null($formula)) {
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

        $formula->update($data);
        return response([
            'message' => 'Formula Updated Successfully',
            'data' => $formula
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recipes $formula)
    {
        $formula->delete();
        return response([
            'message' => 'Formula Deleted Successfully',
            'data' => $formula
        ], 200);
    }
}
