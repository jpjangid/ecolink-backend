<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function blogs()
    {
        /* Getting all records */
        $blogs = DB::table('blogs')->where('flag', 0)->get();

        if($blogs->isNotEmpty()){
            return response()->json(['blogs' => $blogs], 200);
        }else{
            return response()->json(['error' => 'No Data Found'], 400);
        }
    }

    public function blog(Request $request)
    {
        /* Getting blog by slug */
        //Getting Page Using Slug
        $validator = Validator::make($request->all(), [
            'slug' => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 400);
        }

        $blog = DB::table('blogs')->where('slug', $request->slug)->first();

        if(!empty($blog)){
            return response()->json(['blog' => $blog], 200);
        }else{
            return response()->json(['error' => 'No Data Found'], 400);
        }
    }
}
