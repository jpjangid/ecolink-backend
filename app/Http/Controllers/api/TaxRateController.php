<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Http\JsonResponse;

class TaxRateController extends Controller
{
    public function getTaxByZip(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'zip' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }
        $tax = DB::table('tax_rates')->select('rate')->where('zip', $request->zip)->first();
        if (!empty($tax)) {
            return response()->json(['message' => 'Tax fetched successfully', 'code' => 200, 'data' => $tax], 200);
        } else {
            return response()->json(['message' => 'No Tax Found', 'code' => 400], 400);
        }
    }

    public function taxExempt(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required',
        ]);
        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }
        $user = $request->user();
        if (!empty($user)) {
            $user->tax_exempt = $user->tax_exempt == 0 ? 1 : 0;
            $user->update();
            return response()->json(['message' => 'User updated successfully', 'code' => 200, 'data' => $user], 200);
        } else {
            return response()->json(['message' => 'No User Found', 'code' => 400], 400);
        }
    }

    public function getCityList()
    {
        $cities = DB::table('tax_rates')->select('id','state_code','state_name','zip','city')->where('city','!=',NULL)->distinct('zip')->orderBy('state_code','asc')->get();

        return $cities;
    }
}
