<?php

namespace App\Http\Controllers;

use App\Models\StaticValue;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class StaticValueController extends Controller
{
    public function index()
    {
        if (checkpermission('StaticValueController@index')) {
            if (request()->ajax()) {
                /* Getting all records */
                $allstaticvalues = DB::table('static_values')->select('id', 'name', 'type', 'value')->orderby('created_at','desc')->get();

                /* Converting Selected Data into desired format */
                $staticvalues = new Collection;
                foreach ($allstaticvalues as $staticvalue) {
                    $staticvalues->push([
                        'id'            => $staticvalue->id,
                        'name'          => $staticvalue->name,
                        'type'          => $staticvalue->type,
                        'value'         => $staticvalue->value,
                    ]);
                }

                /* Sending data through yajra datatable for server side rendering */
                return Datatables::of($staticvalues)
                    ->addIndexColumn()
                    /* Adding Actions like edit, delete and show */
                    ->addColumn('action', function ($row) {
                        $delete_url = url('admin/staticvalues/delete', $row['id']);
                        $edit_url = url('admin/staticvalues/edit', $row['id']);
                        $btn = '
                            <div style="display:flex;">
                            <a class="btn btn-primary btn-xs" href="' . $edit_url . '" style="margin-right: 2px;"><i class="fas fa-edit"></i></a>
                                        </div>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('staticvalues.index');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
       //
    }

    public function edit($id)
    {
        $value = DB::table('static_values')->where('id',$id)->first();

        return view('staticvalues.edit', compact('value','id'));
    }

    public function update(Request $request, $id)
    {
        /* Validating Input fields */
        $request->validate([
            'name'      =>  'required',
            'type'      =>  'required',
            'value'     =>  'required',
        ]);

        /* Storing Data in Table */
        StaticValue::where('id',$id)->update([
            'name'      => $request->name,
            'value'     => $request->value,
            'type'      => $request->type
        ]);

        /* After Successfull insertion of data redirecting to listing page with message */
        return redirect('admin/staticvalues')->with('success', 'Static Value Updated successfully');
    }

    public function destroy($id)
    {
        //
    }
}
