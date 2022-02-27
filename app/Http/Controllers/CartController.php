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
        if (request()->ajax()) {
            /* Getting all records */
            $allcarts = Cart::select('id', 'user_id', 'product_id', 'quantity')->with('user:id,name', 'product:id,name')->get();

            /* Converting Selected Data into desired format */
            $carts = new Collection;
            foreach ($allcarts as $cart) {
                $carts->push([
                    'id'            => $cart->id,
                    'user'          => $cart->user->name,
                    'product'       => $cart->product->name,
                    'quantity'      => $cart->quantity,
                    'created_at'    => date('d-m-Y h:i A', strtotime($cart->created_at)),
                ]);
            }

            /* Sending data through yajra datatable for server side rendering */
            return Datatables::of($carts)
                ->addIndexColumn()
                /* Adding Actions like edit, delete and show */
                ->addColumn('action', function ($row) {
                    $delete_url = url('admin/carts/delete', $row['id']);
                    $edit_url = url('admin/carts/edit', $row['id']);
                    $btn = '';
                    // $btn = '<a class="btn btn-primary btn-xs ml-1" href="' . $edit_url . '"><i class="fas fa-edit"></i></a>';
                    $btn .= '<a class="btn btn-danger btn-xs ml-1" href="' . $delete_url . '"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('carts.index');
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy($id)
    {
        /* Deleting Entry from table */
        Cart::where('id', $id)->delete();

        return redirect('admin/carts')->with('danger', 'Entry Deleted');
    }
}
