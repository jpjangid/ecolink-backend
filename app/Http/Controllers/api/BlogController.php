<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class BlogController extends Controller
{
    public function blogs()
    {
        /* Getting all records */
        $blogs = DB::table('blogs')->where('flag', 0)->get();

        if($blogs->isNotEmpty()){
            return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $blogs], 200);
        }else{
            return response()->json(['message' => 'No Data Found', 'code' => 400], 400);
        }
    }

    public function blog(Request $request)
    {
        /* Getting blog by slug */
        $validator = Validator::make($request->all(), [
            'slug' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        $blog = DB::table('blogs')->where(['slug' => $request->slug, 'status' => 1])->first();

        if(!empty($blog)){
            return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $blog], 200);
        }else{
            return response()->json(['message' => 'No Data Found', 'code' => 400], 400);
        }
    }
}
