<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Deliveries;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class DeliveryController extends Controller
{
    public function store(Request $request)
    {
        $data = $request->all();
        $delivery = Deliveries::create($data);
        return response([
            'message' => 'The delivery has been successfully added.',
            'data' => $delivery
        ], 200);
    }
}
