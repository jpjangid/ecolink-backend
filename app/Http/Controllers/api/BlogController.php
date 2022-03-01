<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BlogController extends Controller
{
    public function blogs()
    {
        /* Getting all records */
        $blogs = DB::table('blogs')->where('flag', 0)->get();
        return response()->json(['blogs' => $blogs], 200);
    }

    public function blog(Request $request)
    {
        /* Getting blog by slug */
        $blog = DB::table('blogs')->where('slug', $request->slug)->first();
        return response()->json(['blog' => $blog], 200);
    }
}
