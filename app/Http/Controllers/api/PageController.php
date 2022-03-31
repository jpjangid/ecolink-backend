<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class PageController extends Controller
{
    public function index(Request $request)
    {
        /* Getting Page Using Slug */
        $validator = Validator::make($request->all(), [
            'slug' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $page = DB::table('pages')->where(['status' => 1, 'slug' => $request->slug])->first();

        if (!empty($page)) {
            return response()->json(['page' => $page], 200);
        } else {
            return response()->json(['error' => 'No Data Found'], 400);
        }
    }
}
