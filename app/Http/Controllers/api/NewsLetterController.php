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
            return response()->json(['error' => $validator->errors()], 400);
        }

        NewsLetter::create([
            'email'     =>  $request->email,
        ]);

        return response()->json(['success' => 'Newsletter Subscribed Successfully'], 200);
    }
}
