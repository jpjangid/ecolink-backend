<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class CartController extends Controller
{
    public function index()
    {
        if (checkpermission('CartController@index')) {
            if (request()->ajax()) {
                /* Getting all records */
                $allcarts = Cart::select('id', 'user_id', 'product_id', 'quantity', 'created_at')->with('user:id,name', 'product:id,name')->orderby('created_at','desc')->get();

                /* Converting Selected Data into desired format */
                $carts = new Collection;
                foreach ($allcarts as $cart) {
                    $carts->push([
                        'id'            => $cart->id,
                        'user'          => $cart->user->name,
                        'product'       => $cart->product->name,
                        'quantity'      => $cart->quantity,
                        'created_at'    => date('d-m-Y H:i', strtotime($cart->created_at)),
                    ]);
                }

                /* Sending data through yajra datatable for server side rendering */
                return Datatables::of($carts)
                    ->addIndexColumn()
                    /* Adding Actions like edit, delete and show */
                    ->addColumn('action', function ($row) {
                        $delete_url = url('admin/carts/delete', $row['id']);
                        $edit_url = url('admin/carts/edit', $row['id']);
                        $btn = '
                            <div style="display:flex;">
                            <a class="btn btn-primary btn-xs" href="' . $edit_url . '" style="margin-right: 2px;"><i class="fas fa-edit"></i></a>
                                <form action="' . $delete_url . '" method="post">
                                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button class="delete btn btn-danger btn-xs cart_confirm"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('carts.index');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function create()
    {
        if (checkpermission('CartController@create')) {
            $users = DB::table('users')->select('id', 'name')->where('role_id', '!=', 1)->get();
            $products = DB::table('products')->where('status', 1)->get();
            return view('carts.create', compact('users', 'products'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id'       =>  'required',
            'product_id'    =>  'required',
            'quantity'      =>  'required'
        ], [
            'user_id.required'       =>  'Please Select User',
            'product_id.required'    =>  'Please Select Product',
            'quantity.required'      =>  'Enter Quantity',
        ]);

        $cart = Cart::where(['user_id' => $request->user_id, 'product_id' =>  $request->product_id])->first();

        if (!empty($cart)) {
            return redirect('admin/carts')->with('danger', 'Duplicate entry');
        } else {
            Cart::create([
                'user_id'       =>  $request->user_id,
                'product_id'    =>  $request->product_id,
                'quantity'      =>  $request->quantity,
            ]);
        }

        return redirect('admin/carts')->with('success', 'Entry added successfully');
    }

    public function edit($id)
    {
        if (checkpermission('CartController@edit')) {
            $cart = DB::table('carts')->find($id);
            $users = DB::table('users')->select('id', 'name')->where('role_id', '!=', 1)->get();
            $products = DB::table('products')->where('status', 1)->get();
            return view('carts.edit', compact('users', 'products', 'cart'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'user_id'       =>  'required',
            'product_id'    =>  'required',
            'quantity'      =>  'required'
        ]);

        Cart::find($id)->update([
            'user_id'       =>  $request->user_id,
            'product_id'    =>  $request->product_id,
            'quantity'      =>  $request->quantity,
        ]);

        return redirect('admin/carts')->with('success', 'Entry updated successfully');
    }

    public function destroy($id)
    {
        if (checkpermission('CartController@destroy')) {
            /* Deleting Entry from table */
            Cart::where('id', $id)->delete();

            return redirect('admin/carts')->with('danger', 'Entry Deleted');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }
}
