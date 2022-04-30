<?php

namespace App\Http\Controllers;

use App\Models\UserAddress;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Collection;

class UserAddressController extends Controller
{
    public function index()
    {
        if (checkpermission('UserAddressController@index')) {
            if (request()->ajax()) {
                /* Getting all records */
                $alladdresses = UserAddress::select('id', 'user_id', 'name', 'email', 'address', 'mobile', 'city', 'state', 'zip')->get();

                /* Converting Selected Data into desired format */
                $addresses = new Collection;
                foreach ($alladdresses as $address) {
                    $addresses->push([
                        'id'            =>  $address->id,
                        'name'          =>  $address->name,
                        'email'         =>  $address->email,
                        'address'       =>  $address->address,
                        'mobile'        =>  $address->mobile,
                        'city'          =>  $address->city,
                        'state'         =>  $address->state,
                        'zip'           =>  $address->zip,
                    ]);
                }

                /* Sending data through yajra datatable for server side rendering */
                return Datatables::of($addresses)
                    ->addIndexColumn()
                    /* Adding Actions like edit, delete and show */
                    ->addColumn('action', function ($row) {
                        $btn = '';
                        $delete_url = url('admin/addresses/delete', $row['id']);
                        // $edit_url = url('admin/addresses/edit', $row['id']);
                        // $btn .= '<a class="btn btn-primary btn-xs ml-1" href="' . $edit_url . '"><i class="fas fa-edit"></i></a>';
                        $btn .= '<a class="btn btn-danger btn-xs ml-1" href="' . $delete_url . '"><i class="fa fa-trash"></i></a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('addresses.index');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function destroy($id)
    {
        if (checkpermission('UserAddressController@destroy')) {
            UserAddress::find($id)->delete();

            return redirect()->back()->with('danger', 'Address Deleted Successfully');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }
}
