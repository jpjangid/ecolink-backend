<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class BlogController extends Controller
{
    public function blogs(Request $request): JsonResponse
    {
        /* Getting all records */
        $query = DB::table('blogs')
            ->select('id', 'slug', 'title', 'image', 'alt', 'short_desc', 'publish_date')
            ->where(['flag' => 0, 'status' => 1])
            ->orderByDesc('publish_date');
        if ($request->filled('squery'))
        {
            $query = $query->where('title', 'like','%' . $request->squery . '%');
        }
        if ($request->filled('category'))
        {
            $query = $query->where('category', 'like', '%' . $request->category . '%');
        }
        if ($request->filled('dateFrom') && $request->filled('dateTo')) {
            $query = $query->whereBetween('publish_date', [$request->dateFrom, $request->dateTo]);
        } else if ($request->filled('dateFrom')) {
            $query = $query->where('publish_date', '>=', $request->dateFrom);
        } else if ($request->filled('dateTo')) {
            $query = $query->where('publish_date', '<=', $request->dateTo);
        }
        $blogs = $query->paginate(20);
        if ($blogs->isNotEmpty()) {
            foreach ($blogs as $blog) {
                $blog->image = url('storage/blogs', $blog->image);
            }
            $categories = DB::table('blog_categories')
            ->where(['flag' => 0])
            ->orderBy('blog_category')
            ->get();
            return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $blogs, $categories], 200);
        } else {
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
