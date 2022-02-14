<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;

class LocationController extends Controller
{

    public function getState()
    {
        $data['states'] = Location::select('state')->distinct()->orderby('state')->get();
        return response()->json($data);
    }
    public function getCity(Request $request)
    {
        $data['cities'] = Location::select('city')->where("state", $request->state)->distinct()->orderby('city')->get();
        return response()->json($data);
    }

    public function getPincode(Request $request)
    {
        $data['pincodes'] = Location::select('pincode')->where("city", $request->city)->get();
        return response()->json($data);
    }
}
