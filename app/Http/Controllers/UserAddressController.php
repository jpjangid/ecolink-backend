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
                $alladdresses = UserAddress::select('id', 'user_id', 'name', 'email', 'address', 'mobile', 'city', 'state', 'zip')->orderby('created_at','desc')->get();

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
                        $delete_url = url('admin/addresses/delete', $row['id']);
                        $btn = '<div style="display:flex;">
                        <form action="' . $delete_url . '" method="post">
                          <input type="hidden" name="_token" value="' . csrf_token() . '">
                          <input type="hidden" name="_method" value="DELETE">
                          <button class="delete btn btn-danger btn-xs address_confirm"><i class="fas fa-trash"></i></button>
                       </form>
                      </div>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'active'])
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
