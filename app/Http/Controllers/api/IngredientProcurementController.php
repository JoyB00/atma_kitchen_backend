<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\IngredientProcurements;
use App\Models\IngredientsProcurementDetails;
use App\Models\Ingredients;
use App\Models\Employees;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class IngredientProcurementController extends Controller
{
    public function index()
    {
        $data = IngredientProcurements::with('IngredientsProcurementDetails', 'IngredientsProcurementDetails.Ingredients')->get();
        $detail = IngredientsProcurementDetails::with('Ingredients')->get();
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
        $data = IngredientProcurements::find($id);
        $detail = IngredientsProcurementDetails::with('Ingredients')->where('ingredient_procurement_id', $id)->get();

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

    public function store(Request $request)
    {
        $data = $request->all();
        $validate = Validator::make($data, [
            'procurement_date' => "required|date",
            'total_price' => 'required|numeric|min:1',
            'detail' => "required",
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first(),
            ], 400);
        }

        $detail = $request->detail;
        foreach ($detail as $item) {
            $validateDetail = Validator::make($item, [
                'quantity' => 'required|numeric|min:1',
                'price' => 'required|numeric|min:1',
                'total_price' => 'required|numeric|min:1'
            ]);
            if ($validateDetail->fails()) {
                return  response(['message' => $validateDetail->errors()->first()], 400);
            } else if ($item['ingredient_id'] == "") {
                return  response(['message' => "The ingredient field is required."], 400);
            }
        }
        $employee = Employees::where('user_id', auth()->user()->id)->first();
        $data['employee_id'] = $employee->id;
        $ingredientProcurement = IngredientProcurements::create($data);
        foreach ($detail as $item) {
            IngredientsProcurementDetails::create([
                'ingredient_procurement_id' => $ingredientProcurement['id'],
                'ingredient_id' => $item['ingredient_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total_price' => $item['total_price'],
            ]);
            $ingredient = Ingredients::where('id', $item['ingredient_id'])->first();
            $ingredient['quantity'] = $ingredient['quantity'] + $item['quantity'];
            $ingredient->save();
        }

        return response([
            'message' => "Ingredient procurement created successfully",
            'data' => $ingredientProcurement,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $data = IngredientProcurements::find($id);
        if (is_null($data)) {
            return response([
                'message' => 'Data Not Found'
            ], 404);
        }
        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'procurement_date' => "required|date",
            'total_price' => 'required|numeric|min:1',
            'detail' => "required",
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first(),
            ], 400);
        }
        $detail = $request->detail;
        foreach ($detail as $item) {
            $validateDetail = Validator::make($item, [
                'quantity' => 'required|numeric|min:1',
                'price' => 'required|numeric|min:1',
                'total_price' => 'required|numeric|min:1'
            ]);
            if ($validateDetail->fails()) {
                return  response(['message' => $validateDetail->errors()->first()], 400);
            } else if ($item['ingredient_id'] == "") {
                return  response(['message' => "The ingredient field is required."], 400);
            }
        }
        $oldDetail = IngredientsProcurementDetails::where('ingredient_procurement_id', $id)->get();
        $data->update($updateData);
        foreach ($oldDetail as $item) {
            $ingredient = Ingredients::find($item['ingredient_id']);
            $ingredient['quantity'] -= $item['quantity'];
            $ingredient->save();
            $item->delete();
        }
        foreach ($detail as $item) {
            IngredientsProcurementDetails::create([
                'ingredient_procurement_id' => $data['id'],
                'ingredient_id' => $item['ingredient_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total_price' => $item['total_price'],
            ]);
            $ingredient = Ingredients::where('id', $item['ingredient_id'])->first();
            $ingredient['quantity'] = $ingredient['quantity'] + $item['quantity'];
            $ingredient->save();
        }

        return response([
            'message' => 'Ingredient Procurement Updated Successfully',
            'data' => $data,
        ], 200);
    }

    public function destroy($id)
    {
        $data = IngredientProcurements::find($id);
        if (is_null($data)) {
            return response([
                'message' => 'Data Not Found'
            ], 404);
        }
        if ($data->delete()) {
            return response([
                'message' => 'Ingredient Procurement Deleted Successfully',
                'data' => $data
            ], 200);
        }
        return  response([
            'message' => 'Delete Product Failed',
            'data' => null,
        ], 400);
    }
}
