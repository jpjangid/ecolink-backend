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
        $blogs = DB::table('blogs')->select('id','slug','title','image','alt', 'short_desc')->where(['flag' => 0, 'status' => 1])->paginate(20);
        $categories = DB::table('blog_categories')->select('id', 'blog_category')->get();

        if($blogs->isNotEmpty()){
            foreach($blogs as $blog){
                $blog->image = url('storage/blogs',$blog->image);
            }
            return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $blogs, $categories], 200);
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
            $blog->image = asset('storage/blogs/'.$blog->image);
            $blog->og_image = asset('storage/blogs/og_image/'.$blog->og_image);
            return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $blog], 200);
        }else{
            return response()->json(['message' => 'No Data Found', 'code' => 400], 400);
        }
    }
}
