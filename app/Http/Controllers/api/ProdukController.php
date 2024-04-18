<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use Illuminate\Support\Facades\Validator;

class ProdukController extends Controller
{
    public function index()
    {
        $produk = Product::get();
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
        $product = Product::where('id', $id)->first();
        if (is_null($product)) {
            return response([
                'message' => "Product Not Found",
            ], 404);
        }

        return response([
            'message' => 'Retrieve Product Successfully',
            'data' => $product
        ], 200);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'id_kategori' => 'required',
            'nama_produk' => 'required',
            'kuantitas' => 'required',
            'harga_produk' => 'required',
            // 'foto_produk' => 'required|image:jpeg,png,jpg',
            'deskripsi' => 'required',
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }

        $produk = Product::create($storeData);
        return response([
            'message' => 'Product Added Successfully',
            'data' => $produk,
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
            'id_kategori' => 'required',
            'nama_produk' => 'required',
            'kuantitas' => 'required',
            'harga_produk' => 'required',
            // 'foto_produk' => 'required|image:jpeg,png,jpg',
            'deskripsi' => 'required',
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first()
            ], 400);
        }

        $produk->update($updateData);
        return response([
            'message' => 'Product Updated Successfully',
            'data' => $produk,
        ], 200);
    }

    public function destroy($id)
    {
        $produk = Product::find($id);
        if (is_null($produk)) {
            return response([
                'message' => 'Product Not Found',
                'data' => null
            ], 404);
        }
        if ($produk->delete()) {
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
