<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class KategoriController extends Controller
{
    public function index()
    {
        $kategori = Kategori::get();
        return response([
            'message' => 'All Category Retrieved',
            'data' => $kategori,
        ], 200);
    }

    public function store(Request $request)
    {
        $storeData = $request->all();
        $validate = Validator::make($storeData, [
            'nama_kategori' => 'required',
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }

        $kategori = Kategori::create($storeData);
        return response([
            'message' => 'Category Created Successfully',
            'data' => $kategori,
        ], 200);
    }

    public function update(Request $request, $id)
    {
        $kategori = Kategori::find($id);
        if (is_null($kategori)) {
            return response([
                'message' => 'Category Not Found',
                'data' => null
            ], 404);
        }

        $newKategori = $request->all();
        $validate = Validator::make($newKategori, [
            'nama_kategori' => 'required'
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()
            ], 400);
        }

        $kategori->update($newKategori);
        return response([
            'message' => 'Category Updated Successfully',
            'data' => $kategori
        ], 200);
    }

    public function destroy($id)
    {
        $kategory = Kategori::find($id);
        if (is_null($kategory)) {
            return response([
                'message' => 'Category Not Found',
                'data' => null
            ], 404);
        }
        if ($kategory->delete()) {
            return response([
                'message' => 'Category Deleted Successfully',
                'data' => $kategory
            ], 200);
        }

        return response([
            'message' => 'Delete Category Failed',
            'data' => null
        ], 400);
    }
}
