<?php

namespace App\Http\Controllers\api;

use App\Http\Controllers\Controller;
use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class WishlistController extends Controller
{
    public function getWishlistItems(Request $request)
    {
        //Get wishlist Items By User id with Product Detail
        $wishlists = Wishlist::select('id','user_id','product_id')->where('user_id', $request->user_id)->with('product:id,name,sale_price,image,alt')->get();

        if($wishlists->isNotEmpty()){
            foreach($wishlists as $wishlist){
                $wishlist->product->image = asset('storage/products/'.$wishlist->product->image);
            }

            return response()->json(['message' => 'Data fetched Successfully', 'code' => 200, 'data' => $wishlists], 200);
        }else{
            return response()->json(['message' => 'No Product Found in wishlist', 'code' => 400], 400);
        }
    }

    public function addWishlistItems(Request $request)
    {
        /* Storing wishlist Items */
        $validator = Validator::make($request->all(), [
            'user_id'       => 'required',
            'product_id'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        $wishlist = Wishlist::where(['user_id' => $request->user_id, 'product_id' => $request->product_id])->first();

        if(!empty($wishlist)){
            return response()->json(['message' => 'Item already present in Wishlist', 'code' => 200], 200);
        }else{
            wishlist::create([
                'user_id'       =>  $request->user_id,
                'product_id'    =>  $request->product_id,
            ]);
        }

        return response()->json(['message' => 'Item added in Wishlist successfully', 'code' => 200], 200);
    }

    public function deleteWishlistItems(Request $request)
    {
        /* Deleting wishlist Items */
        $validator = Validator::make($request->all(), [
            'user_id'       => 'required',
            'product_id'    => 'required',
        ]);

        if ($validator->fails()) {
            return response()->json(['message' => $validator->errors(), 'code' => 400], 400);
        }

        $wishlist = Wishlist::where(['user_id' => $request->user_id, 'product_id' => $request->product_id])->first();

        if(!empty($wishlist)){
            $wishlist->delete();

            return response()->json(['message' => 'Item deleted from Wishlist', 'code' => 200], 200);
        }else{
            return response()->json(['message' => 'No item found in wishlist', 'code' => 400], 400);
        }
    }
}
