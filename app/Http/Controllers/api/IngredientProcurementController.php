<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IngredientProcurements;
use App\Models\IngredientsProcurementDetails;
use Illuminate\Http\Request;

class IngredientProcurementController extends Controller
{
    public function index()
    {
        $data = IngredientProcurements::get();
        $detail = IngredientsProcurementDetails::get();
        if (is_null($data)) {
            return response([
                'message' => 'Ingredient Procurement Not Found',
            ], 404);
        }
        return response([
            'message' => 'All Ingredient Procurement Retrivied',
            'data' => [
                'ingredientProcurement' => $data,
                'detail' => $detail
            ],
        ], 200);
    }

    public function getIngredientProcurement($id)
    {
        $data = IngredientProcurements::where('id', $id)->get();
        $detail = IngredientsProcurementDetails::where('ingredient_procurement_id', $id)->get();

        if (is_null($data)) {
            return response([
                'message' => 'Data Not Found',
            ], 404);
        }

        return response([
            'message' => 'Retrieved Data Successfully',
            'data' => [
                'ingredient_procurement' => $data,
                'details' => $detail
            ]
        ], 200);
    }
}
