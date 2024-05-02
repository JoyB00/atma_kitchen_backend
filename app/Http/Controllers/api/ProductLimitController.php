<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProductLimits;
use Illuminate\Http\Request;

class ProductLimitController extends Controller
{
    public function getLimitByDate(Request $request, $id)
    {
        $limit = ProductLimits::where('production_date', $request->production_date)->where('product_id', $id)->first();
        if (is_null($limit)) {
            return response([
                'message' => $request->production_date,
            ], 404);
        }

        return response([
            'message' => 'Daily Stock Limit available',
            'data' => $limit,
        ], 200);
    }
}
