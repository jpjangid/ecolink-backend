<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class NoticeController extends Controller
{
    public function getNotice()
    {
        $notice = DB::table('notices')->first();

        if(!empty($notice)){
            $notice->image = asset('storage/notices/'.$notice->image);

            return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $notice], 200);
        }else{
            return response()->json(['message' => 'Data not found', 'code' => 400], 400);
        }

    }
}
