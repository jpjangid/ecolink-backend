<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Page;
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
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        $page = Page::where(['status' => 1, 'slug' => $request->slug])->with('links:page_id,link_id', 'links.relatedPage:id,title,slug')->where('status', 1)->first();

        if (!empty($page)) {
            $page->image = asset('storage/pages/' . $page->image);

            return response()->json(['message' => 'Data fetched Successfully', 'data' => $page], 200);
        } else {
            return response()->json(['message' => 'No Data Found', 'code' => 400], 400);
        }
    }
}
