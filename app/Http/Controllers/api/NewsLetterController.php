<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\NewsLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
class NewsLetterController extends Controller
{
    public function index(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email'     =>  'required|email',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        NewsLetter::create([
            'email'     =>  $request->email,
        ]);

        return response()->json(['message' => 'Newsletter Subscribed Successfully', 'code' => 200], 200);
    }
}
