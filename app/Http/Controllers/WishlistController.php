<?php

namespace App\Http\Controllers;

use App\Models\Wishlist;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class WishlistController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            /* Getting all records */
            $allwishlists = Wishlist::select('id', 'user_id', 'product_id')->with('user:id,name', 'product:id,name')->get();

            /* Converting Selected Data into desired format */
            $wishlists = new Collection;
            foreach ($allwishlists as $wishlist) {
                $wishlists->push([
                    'id'            => $wishlist->id,
                    'user'          => $wishlist->user->name,
                    'product'       => $wishlist->product->name,
                    'created_at'    => date('d-m-Y h:i A', strtotime($wishlist->created_at)),
                ]);
            }

            /* Sending data through yajra datatable for server side rendering */
            return Datatables::of($wishlists)
                ->addIndexColumn()
                /* Adding Actions like edit, delete and show */
                ->addColumn('action', function ($row) {
                    $delete_url = url('admin/wishlists/delete', $row['id']);
                    $edit_url = url('admin/wishlists/edit', $row['id']);
                    $btn = '';
                    // $btn = '<a class="btn btn-primary btn-xs ml-1" href="' . $edit_url . '"><i class="fas fa-edit"></i></a>';
                    $btn .= '<a class="btn btn-danger btn-xs ml-1" href="' . $delete_url . '"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('wishlists.index');
    }

    public function destroy($id)
    {
        /* Deleting Entry from table */
        Wishlist::where('id', $id)->delete();

        return redirect('admin/wishlists')->with('danger', 'Entry Deleted');
    }
}
