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
    // retrieve all address from currently logged in user
    $address = Addresses::where('id_customer', auth()->user()->customer->id)->get();
    return response([
      'message' => 'All Address Retrieved',
      'data' => $address,
    ], 200);
  }

  public function show($id)
  {
    $address = Addresses::find($id);
    if (is_null($address)) {
      return response([
        'message' => 'Address Not Found',
        'data' => null
      ], 404);
    }

    return response([
      'message' => 'Address Retrieved',
      'data' => $address
    ], 200);
  }

  public function store(Request $request)
  {
    $storeData = $request->all();
    $validate = Validator::make($storeData, [
      'customer_id' => 'required',
      'subdistrict' => 'required',
      'city' => 'required',
      'postal_code' => 'required',
      'complete_address' => 'required'
    ]);

    if ($validate->fails()) {
      return response([
        'message' => $validate->errors()->first()
      ], 400);
    }

    $address = Addresses::create($storeData);
    return response([
      'message' => 'Address Created Successfully',
      'data' => $address,
    ], 200);
  }

  public function update(Request $request, $id)
  {
    // find address with id X
    $address = Addresses::find($id);
    if (is_null($address)) {
      return response([
        'message' => 'Address Not Found',
        'data' => null
      ], 404);
    }

    $newAddress = $request->all();
    $validate = Validator::make($newAddress, [
      'customer_id' => 'rhequired',
      'subdistrict' => 'required',
      'city' => 'required',
      'postal_code' => 'required',
      'complete_address' => 'required'
    ]);

    if ($validate->fails()) {
      return response([
        'message' => $validate->errors()->first()
      ], 400);
    }

    $address->update($newAddress);
    return response([
      'message' => 'Address Updated Successfully',
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
      'message' => 'Delete Address Failed',
      'data' => null
    ], 400);
  }
}
