<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\ProductLimits;
use App\Models\Recipes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class ProductController extends Controller
{
    public function index()
    {
        $produk = Product::with('Categories')->where('active', 1)->get();
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
        $recipe = Recipes::with('Ingredients')->where('product_id', $id)->get();
        $limitProduct = ProductLimits::where('product_id', $id)->latest()->first();
        $allLimit =  ProductLimits::where('product_id', $id)->get();
        if (is_null($product)) {
            return response([
                'message' => "Product Not Found",
            ], 404);
        }

        return response([
            'message' => 'Retrieve Product Successfully',
            'data' => [
                'product' => $product,
                'recipe' => $recipe,
                'limit' => $limitProduct,
                'allLimit' => $allLimit
            ]

        ], 200);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'product_name' => 'required',
            'category_id' => 'required',
            'ready_stock' => 'required|numeric|min:0',
            'daily_stock' => 'required|numeric|min:0',
            'product_price' => 'required|numeric|min:1',
            'product_picture' => 'required|image:jpeg,png,jpg',
            'description' => 'required',
            'product_status' => 'required',
            'production_date' => 'date_format:Y-m-d|before:today'
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        } else if ($storeData['product_status'] == 'Pre-Order' && $storeData['category_id'] == 4) {
            return response([
                'message' => 'Product Status must be Ready',
            ], 400);
        } else if ($storeData['product_status'] == 'Pre-Order' && !isset($storeData['production_date'])) {
            return response([
                'message' => 'The production date field is required.',
            ], 400);
        } else if ($storeData['product_status'] == 'Pre-Order' && $storeData['limit_amount'] < 1) {
            return response([
                'message' => 'The daily stock limit field must be at least 1.',
            ], 400);
        } else if ($storeData['category_id'] != 4 && !isset($storeData['recipe'])) {
            return response([
                'message' => 'The recipes field is required.',
            ], 400);
        } else if ($storeData['category_id'] == 4 && isset($storeData['recipe'])) {
            return response([
                "message" => "Product category does not accept recipes"
            ], 400);
        }


        $uploadFolder = 'product';
        $image = $request->file('product_picture');
        $imageUploadedPath = $image->store($uploadFolder, 'public');
        $uploadedImageResponse = basename($imageUploadedPath);

        $storeData['product_picture'] = $uploadedImageResponse;

        $recipe = $request->recipe;
        if ($storeData['category_id'] != 4) {
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
        }

        $product = Product::create($storeData);
        if ($product['product_status'] == 'Pre-Order') {
            ProductLimits::create([
                'product_id' => $product->id,
                'limit_amount' => $storeData['limit_amount'],
                'production_date' => $storeData['production_date'],
            ]);
        }
        if ($product['category_id'] != 4) {
            foreach ($recipe as $item) {
                Recipes::create([
                    'product_id' => $product['id'],
                    'ingredient_id' => $item['ingredient_id'],
                    'quantity' => $item['quantity'],
                ]);
            }
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
            'ready_stock' => 'required|numeric|min:0',
            'daily_stock' => 'required|numeric|min:0',
            'product_price' => 'required|numeric|min:1',
            'description' => 'required',
            'production_date' => 'date_format:Y-m-d|before:today'
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        } else if ($updateData['product_status'] == 'Pre-Order' && $updateData['category_id'] == 4) {
            return response([
                'message' => 'Product Status must be Ready',
            ], 400);
        } else if ($updateData['product_status'] == 'Pre-Order' && !isset($updateData['production_date'])) {
            return response([
                'message' => 'The production date field is required.',
            ], 400);
        } else if ($updateData['product_status'] == 'Pre-Order' && $updateData['limit_amount'] < 1) {
            return response([
                'message' => 'The daily stock limit field must be at least 1.',
            ], 400);
        } else if ($updateData['category_id'] != 4 && !isset($updateData['recipe'])) {
            return response([
                'message' => 'The recipes field is required.',
            ], 400);
        } else if ($updateData['category_id'] == 4 && isset($updateData['recipe'])) {
            return response([
                "message" => "Product category does not accept recipes"
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
        if ($updateData['category_id'] != 4) {
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
        }
        $produk->update($updateData);
        if ($produk['product_status'] == "Pre-Order") {
            $product_limit = ProductLimits::where('product_id', $id)->where('production_date', $updateData['production_date'])->latest()->first();
            if (is_null($product_limit)) {
                ProductLimits::create([
                    'product_id' => $produk->id,
                    'limit_amount' => $updateData['limit_amount'],
                    'production_date' => $updateData['production_date'],
                ]);
            } else {
                $product_limit->update([
                    'limit_amount' => $updateData['limit_amount'],
                ]);
            }
        }


        $oldRecipe = Recipes::where('product_id', $id)->get();
        if ($updateData['category_id'] != 4) {
            foreach ($recipe as $item) {
                Recipes::create([
                    'product_id' => $produk['id'],
                    'ingredient_id' => $item['ingredient_id'],
                    'quantity' => $item['quantity'],
                ]);
            }
        }

        if (isset($oldRecipe)) {
            foreach ($oldRecipe as $item) {
                $item->delete();
            }
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
    public function disableProduct($id)
    {
        $produk = Product::find($id);
        if (is_null($produk)) {
            return response([
                'message' => 'Product Not Found',
                'data' => null
            ], 404);
        }
        $produk['active'] = 0;
        $produk->save();
        return  response([
            'message' => 'Product Disabled Successfully',
            'data' => $produk,
        ], 200);
    }
}
