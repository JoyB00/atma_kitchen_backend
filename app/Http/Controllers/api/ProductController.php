<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Recipes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $produk = Product::with('Categories')->get();
        if (is_null($produk)) {
            return response([
                'message' => 'No Data Found',
                'data' => null
            ], 404);
        }
        return response([
            'message' => 'All Product Retrivied',
            'data' => $produk
        ], 200);
    }

    public function getProduct($id)
    {
        $product = Product::with('Categories')->where('id', $id)->first();
        $recipe = Recipes::where('product_id', $id)->get();
        if (is_null($product)) {
            return response([
                'message' => "Product Not Found",
            ], 404);
        }

        return response([
            'message' => 'Retrieve Product Successfully',
            'data' => [
                'product' => $product,
                'recipe' => $recipe
            ]

        ], 200);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'product_name' => 'required',
            'category_id' => 'required',
            'quantity' => 'required|numeric|min:1',
            'product_price' => 'required|numeric|min:1',
            'product_picture' => 'required|image:jpeg,png,jpg',
            'description' => 'required',
            'recipe' => 'required'
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }

        $uploadFolder = 'product';
        $image = $request->file('product_picture');
        $imageUploadedPath = $image->store($uploadFolder, 'public');
        $uploadedImageResponse = basename($imageUploadedPath);

        $storeData['product_picture'] = $uploadedImageResponse;
        $recipe = $request->recipe;
        foreach ($recipe as $item) {
            $validateRecipe = Validator::make($item, [
                'ingredient_id' => 'required',
                'quantity' => 'required|numeric|min:1'
            ]);
            if ($validateRecipe->fails()) {
                return response([
                    'message' => $validateRecipe->errors()->first()
                ], 400);
            }
        }
        $product = Product::create($storeData);
        foreach ($recipe as $item) {
            Recipes::create([
                'product_id' => $product['id'],
                'ingredient_id' => $item['ingredient_id'],
                'quantity' => $item['quantity'],
            ]);
        }
        return response([
            'message' => 'Product Added Successfully',
            'data' => $product,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $produk = Product::find($id);
        if (is_null($produk)) {
            return  response([
                'message' => "Product Not Found",
                'data' => null
            ], 404);
        }
        $updateData = $request->all();
        $validate = Validator::make($updateData, [
            'product_name' => 'required',
            'category_id' => 'required',
            'quantity' => 'required|min:1',
            'product_price' => 'required',
            'description' => 'required',
            'recipe' => 'required'
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }

        if ($request->hasFile('product_picture')) {
            $uploadFolder = 'product';
            $image = $request->file('product_picture');
            $imageUploadedPath = $image->store($uploadFolder, 'public');
            $uploadedImageResponse = basename($imageUploadedPath);

            Storage::disk('public')->delete('product/' . $produk->product_picture);

            $updateData['product_picture'] = $uploadedImageResponse;
        }


        $recipe = $request->recipe;
        foreach ($recipe as $item) {
            $validateRecipe = Validator::make($item, [
                'ingredient_id' => 'required',
                'quantity' => 'required|numeric|min:1'
            ]);
            if ($validateRecipe->fails()) {
                return response([
                    'message' => $validateRecipe->errors()->first()
                ], 400);
            }
        }
        $oldRecipe = Recipes::where('product_id', $id)->get();
        $produk->update($updateData);
        foreach ($recipe as $item) {
            Recipes::create([
                'product_id' => $produk['id'],
                'ingredient_id' => $item['ingredient_id'],
                'quantity' => $item['quantity'],
            ]);
        }
        foreach ($oldRecipe as $item) {
            $item->delete();
        }
        return response([
            'message' => 'Product Updated Successfully',
            'data' => $produk,
        ], 200);
    }

    public function destroy($id)
    {
        $produk = Product::find($id);
        $recipe = Recipes::where('product_id', $id)->get();
        if (is_null($produk)) {
            return response([
                'message' => 'Product Not Found',
                'data' => null
            ], 404);
        }

        Storage::disk('public')->delete('product/' . $produk->product_picture);
        if ($produk->delete()) {
            foreach ($recipe as $item) {
                $item->delete();
            }
            return response([
                'message' => 'Product Deleted Successfully',
                'data' => $produk
            ], 200);
        }
        return  response([
            'message' => 'Delete Product Failed',
            'data' => null,
        ], 400);
    }
}
