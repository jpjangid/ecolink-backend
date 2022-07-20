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
        if (checkpermission('WishlistController@index')) {
            if (request()->ajax()) {
                /* Getting all records */
                $allwishlists = Wishlist::select('id', 'user_id', 'product_id', 'created_at')->with('user:id,name', 'product:id,name')->orderby('created_at','desc')->get();

                /* Converting Selected Data into desired format */
                $wishlists = new Collection;
                foreach ($allwishlists as $wishlist) {
                    $wishlists->push([
                        'id'            => $wishlist->id,
                        'user'          => $wishlist->user->name,
                        'product'       => $wishlist->product->name,
                        'created_at'    => date('d-m-Y H:i', strtotime($wishlist->created_at)),
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
                        $btn = '<div style="display:flex;">
                                        <form action="' . $delete_url . '" method="post">
                                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="delete btn btn-danger btn-sm show_confirm"><i class="fa fa-trash"></i></button>
                                        </form>
                                    </div>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('wishlists.index');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function destroy($id)
    {
        if (checkpermission('WishlistController@destroy')) {
            /* Deleting Entry from table */
            Wishlist::where('id', $id)->delete();

            return redirect('admin/wishlists')->with('danger', 'Entry Deleted');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }
}
