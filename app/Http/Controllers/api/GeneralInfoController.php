<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use App\Models\GeneralInfo;

class GeneralInfoController extends Controller
{
  /**
   * Display a listing of the resource.
   */
  public function index()
  {
    $generalInfo = GeneralInfo::get();
    return response([
      'message' => 'All Info Retrieved',
      'data' => $generalInfo
    ], 200);
  }

  /**
   * Store a newly created resource in storage.
   */
  public function store(Request $request)
  {
    $uploadFolder = 'generalInfo';
    $image = $request->file('picture');
    $imageUploadedPath = $image->store($uploadFolder, 'public');
    $uploadedImageResponse = basename($imageUploadedPath);

    $storeData['picture'] = $uploadedImageResponse;

    $generalInfo = GeneralInfo::create($storeData);
    return response([
      'message' => 'Info Created Successfully',
      'data' => $generalInfo,
    ], 200);
  }
}
