<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Addresses;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class AddressController extends Controller
{
  public function index()
  {
    $address = Addresses::where('id_customer', auth()->user()->customer->id)->get();
    return response([
      'message' => 'All Address Retrieved',
      'data' => $address,
    ], 200);
  }

  public function store(Request $request)
  {
    $storeData = $request->all();
    $validate = Validator::make($storeData, [
      'id_customer' => 'required',
      'subdistrict' => 'required',
      'city' => 'required',
      'postal_code' => 'required',
      'full_address' => 'required'
    ]);

    if ($validate->fails()) {
      return response([
        'message' => $validate->errors()
      ], 400);
    }

    $address = Addresses::create($storeData);
    return response([
      'message' => 'Category Created Successfully',
      'data' => $address,
    ], 200);
  }

  public function update(Request $request, $id)
  {
    $address = Addresses::find($id);
    if (is_null($address)) {
      return response([
        'message' => 'Category Not Found',
        'data' => null
      ], 404);
    }

    $newAddress = $request->all();
    $validate = Validator::make($newAddress, [
      'id_customer' => 'required',
      'subdistrict' => 'required',
      'city' => 'required',
      'postal_code' => 'required',
      'full_address' => 'required'
    ]);

    if ($validate->fails()) {
      return response([
        'message' => $validate->errors()
      ], 400);
    }

    $address->update($newAddress);
    return response([
      'message' => 'Category Updated Successfully',
      'data' => $address
    ], 200);
  }

  public function destroy($id)
  {
    $address = Addresses::find($id);
    if (is_null($address)) {
      return response([
        'message' => 'Address Not Found',
        'data' => null
      ], 404);
    }

    if ($address->delete()) {
      return response([
        'message' => 'Address Deleted Successfully',
        'data' => $address
      ], 200);
    }

    return response([
      'message' => 'Delete Category Failed',
      'data' => null
    ], 400);
  }
}
