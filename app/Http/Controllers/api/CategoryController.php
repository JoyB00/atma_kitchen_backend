<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Categories;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class CategoryController extends Controller
{
    public function index()
    {
        $category = Categories::get();
        return response([
            'message' => 'All Category Retrieved',
            'data' => $category,
        ], 200);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'category_name' => 'required',
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }

        $category = Categories::create($storeData);
        return response([
            'message' => 'Category Created Successfully',
            'data' => $category,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $category = Categories::find($id);
        if (is_null($category)) {
            return response([
                'message' => 'Category Not Found',
                'data' => null
            ], 404);
        }

        $newKategori = $request->all();
        $validate = Validator::make($newKategori, [
            'category_name' => 'required'
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }

        $category->update($newKategori);
        return response([
            'message' => 'Category Updated Successfully',
            'data' => $category
        ], 200);
    }

    public function destroy($id)
    {
        $product = Product::where('category_id', $id)->get();
        $category = Categories::find($id);
        if (is_null($category) || is_null($product)) {
            return response([
                'message' => 'Category Not Found',
                'data' => null
            ], 404);
        }

        if ($category->delete()) {
            if (!is_null($product)) {
                foreach ($product as $p) {
                    $p->delete();
                }
            }
            return response([
                'message' => 'Category Deleted Successfully',
                'data' => $category
            ], 200);
        }

        return response([
            'message' => 'Delete Category Failed',
            'data' => null
        ], 400);
    }
}
