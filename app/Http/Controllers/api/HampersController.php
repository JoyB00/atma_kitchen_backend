<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hampers;
use App\Models\HampersDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class HampersController extends Controller
{
    public function index()
    {
        $hampers = Hampers::with('HampersDetail', 'HampersDetail.Product', 'HampersDetail.Ingredients')->where('active', 1)->orderBy('hampers_name', 'asc')->orderBy('id', 'desc')->get();
        $hampers_detail = HampersDetails::with('Hampers', 'Product', 'Ingredients')->get();
        return response([
            'message' => 'All Hampers Retrivied',
            'data' => [
                'hampers' => $hampers,
                'hampersDetail' => $hampers_detail
            ]
        ], 200);
    }

    public function store(Request $request)
    {
        $data = $request->all();
        $validate = Validator::make($data, [
            'hampers_name' => 'required|unique:hampers',
            'hampers_price' => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:1',
            'hampers_picture' => 'required|image:jpeg,png,jpg',
            'details' => 'required'
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first(),
            ], 400);
        }

        $uploadFolder = "hampers";
        $image = $request->file('hampers_picture');
        $imageUploadPath = $image->store($uploadFolder, 'public');
        $uploadedImageRespose = basename($imageUploadPath);

        $data['hampers_picture'] = $uploadedImageRespose;

        $details =  $request->details;
        foreach ($details as $detail) {
            if (!isset($detail['ingredient_id']) && !isset($detail['product_id'])) {
                return response([
                    'message' => "Please provide either Ingredient or Product for this detail!"
                ], 400);
            }
        }
        $hampers = Hampers::create($data);
        foreach ($details as $detail) {
            if (!isset($detail['ingredient_id'])) {
                HampersDetails::create([
                    'hampers_id' => $hampers['id'],
                    'product_id' => $detail["product_id"],
                ]);
            } else {
                HampersDetails::create([
                    'hampers_id' => $hampers['id'],
                    'ingredient_id' => $detail["ingredient_id"],
                ]);
            }
        }
        return response([
            'message' => 'Hampers Created Successfully',
            'data' => $hampers
        ]);
    }

    public function getHampers($id)
    {
        $hampers = Hampers::find($id);
        $hampers_detail = HampersDetails::with('Product', 'Product.Categories', 'Product.AllLimit', 'Ingredients')->where('hampers_id', $id)->get();

        if (is_null($hampers)) {
            return response([
                'message' => "Hampers not found"
            ], 404);
        }

        return response([
            'message' => 'Retrieve Hampers Successfully',
            'data' => [
                'hampers' => $hampers,
                'details' => $hampers_detail
            ]
        ], 200);
    }


    public function update(Request $request, $id)
    {
        $hampers = Hampers::find($id);
        if (is_null($hampers)) {
            return response([
                'message' => 'Hampers Not Found'
            ], 404);
        }

        $data = $request->all();
        $validate = Validator::make($data, [
            'hampers_name' => 'required',
            'hampers_price' => 'required|numeric',
            'quantity' => 'required|numeric',
            'details' => 'required'
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first(),
            ], 400);
        }

        if ($request->hasFile('hampers_picture')) {
            $uploadFolder = "hampers";
            $image = $request->file('hampers_picture');
            $imageUploadPath = $image->store($uploadFolder, 'public');
            $uploadedImageRespose = basename($imageUploadPath);
            Storage::disk('public')->delete('hampers/' . $hampers->hampers_picture);
            $data['hampers_picture'] = $uploadedImageRespose;
        }

        $details =  $request->details;
        foreach ($details as $detail) {
            if (!isset($detail['ingredient_id']) && !isset($detail['product_id'])) {
                return response([
                    'message' => "Please provide either Ingredient or Product for this detail!"
                ], 400);
            }
        }
        $oldDetail = HampersDetails::where('hampers_id', $id)->get();
        $hampers->update($data);
        foreach ($details as $detail) {
            if (!isset($detail['ingredient_id'])) {
                HampersDetails::create([
                    'hampers_id' => $hampers['id'],
                    'product_id' => $detail["product_id"],
                ]);
            } else {
                HampersDetails::create([
                    'hampers_id' => $hampers['id'],
                    'ingredient_id' => $detail["ingredient_id"],
                ]);
            }
        }
        foreach ($oldDetail as $item) {
            $item->delete();
        }
        return response([
            'message' => 'Hampers Updated Successfully',
            'data' => $hampers
        ], 200);
    }



    public function destroy($id)
    {
        $hampers = Hampers::find($id);
        $details = HampersDetails::where('hampers_id', $id)->get();
        if (is_null($hampers)) {
            return  response([
                'message' => 'Hampers not found'
            ], 404);
        }

        Storage::disk('public')->delete('hampers/' . $hampers->hampers_picture);
        if ($hampers->delete()) {
            foreach ($details as $detail) {
                $detail->delete();
            }
            return response([
                'message' => 'Hampers Deleted Successfully',
                'data' => $hampers
            ], 200);
        }
        return response([
            'message' => 'Delete Hampers Failed',
            'data' => null
        ], 400);
    }

    public function disableHampers($id)
    {
        $hampers = Hampers::find($id);
        if (is_null($hampers)) {
            return response([
                'message' => 'Hampers Not Found',
                'data' => null
            ], 404);
        }
        $hampers['active'] = 0;
        $hampers->save();
        return  response([
            'message' => 'Hampers Disabled Successfully',
            'data' => $hampers,
        ], 200);
    }
}
