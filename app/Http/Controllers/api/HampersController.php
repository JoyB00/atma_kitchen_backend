<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Hampers;
use App\Models\HampersDetails;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class HampersController extends Controller
{
    public function index()
    {
        $hampers = Hampers::get();
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
            'hampers_name' => 'required',
            'hampers_price' => 'required|numeric|min:1',
            'quantity' => 'required|numeric|min:1',
            'hampers_picture' => 'required|image:jpeg,png,jpg',
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
        $hampers = Hampers::create($data);
        return response([
            'message' => 'Hampers Created Successfully',
            'data' => $hampers
        ]);
    }

    public function storeDetail(Request $request)
    {
        $data =  $request->all();
        if (!isset($data['product_id']) && !isset($data['ingredient_id'])) {
            return response([
                'message' => "Please fill in the product or ingredient"
            ], 400);
        }

        $last_hampers = Hampers::orderBy('id', 'desc')->first();
        $data['hampers_id'] = $last_hampers->id + 1;
        $hampers_detail = HampersDetails::create($data);

        return response([
            'message' => 'Added to hampers detail successfully!',
            'data' => $hampers_detail
        ], 200);
    }

    public function  update(Request $request, $id)
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
            'quantity' => 'required|number'
        ]);
        if ($validate->fails()) {
            return response([
                'message' => $validate->errors()->first(),
            ], 400);
        }

        $hampers->update($data);
        return response([
            'message' => 'Hampers Updated Successfully',
            'data' => $hampers
        ], 200);
    }


    public function updateHamperDetails(Request $request, $id)
    {
        $detail = HampersDetails::find($id);
        if (is_null($detail)) {
            return response([
                'message' => 'Hamper Details Not Found',
            ], 404);
        }

        $data =  $request->all();
        if (!isset($data['product_id']) && !isset($data['ingredient_id'])) {
            return response([
                'message' => "Please fill in the product or ingredient"
            ], 400);
        }

        $detail->update();
        return response([
            'message' => 'Hamper Detail Updated Successfully',
            'data' => $detail,
        ], 200);
    }

    public function destroy($id)
    {
        $hampers = Hampers::find($id);
        if (is_null($hampers)) {
            return  response([
                'message' => 'Hampers not found'
            ], 404);
        }

        if ($hampers->delete()) {
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

    public function destroyDiscardHampers()
    {
        $last_hampers = Hampers::orderBy('id', 'desc')->first();
        $hampersDetail = HampersDetails::where('hampers_id', $last_hampers->id + 1)->get();

        if (is_null($hampersDetail)) {
            return  response([
                'message' => 'There are no Hamper Details'
            ], 200);
        }

        foreach ($hampersDetail as $item) {
            $item->delete();
        }
        return  response([
            'message' => 'All Hamper Details Deleted',
            'data' => $hampersDetail
        ], 200);
    }
}
