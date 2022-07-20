<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactUs;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class RequestProductController extends Controller
{
    public function index()
    {
        if (checkpermission('RequestProductController@index')) {
            if (request()->ajax()) {
                /* Getting all records */
                $allcontact = DB::table('contact_us')->select('id', 'first_name', 'last_name', 'phone', 'email', 'created_at')->where(['flag' => '0', 'type' => 'requestproduct'])->orderby('created_at','desc')->get();

                /* Converting Selected Data into desired format */
                $contacts = new Collection;
                foreach ($allcontact as $contact) {
                    $contacts->push([
                        'id'            => $contact->id,
                        'first_name'    => $contact->first_name,
                        'last_name'     => $contact->last_name,
                        'phone'         => $contact->phone,
                        'email'         => $contact->email,
                        'created_at'    => date('d-m-Y H:i', strtotime($contact->created_at)),
                    ]);
                }

                /* Sending data through yajra datatable for server side rendering */
                return Datatables::of($contacts)
                    ->addIndexColumn()
                    /* Adding Actions like edit, delete and show */
                    ->addColumn('action', function ($row) {
                        $delete_url = url('admin/requestproduct/delete', $row['id']);
                        $edit_url = url('admin/requestproduct/edit', $row['id']);
                        $show_url = url('admin/requestproduct/show', $row['id']);
                        $btn = '<div style="display:flex;">
                         <a class="btn btn-info btn-xs ml-1" href="' . $show_url . '" style="margin-right: 2px;"><i class="fa fa-eye"></i></a>
                        <form action="' . $delete_url . '" method="post">
                          <input type="hidden" name="_token" value="' . csrf_token() . '">
                          <input type="hidden" name="_method" value="DELETE">
                          <button class="delete btn btn-danger btn-xs request_confirm"><i class="fas fa-trash"></i></button>
                       </form>
                      </div>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'active'])
                    ->make(true);
            }
            return view('requestproduct.index');
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

    public function show($id)
    {
        if (checkpermission('RequestProductController@edit')) {
            /* Getting Contact with Question to show using Id */
            $contact = ContactUs::where('id', $id)->with('question')->first();

            return view('requestproduct.show', compact('contact'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    // public function edit($id)
    // {
    //     $contact = ContactUs::where('id', $id)->with('question')->first();

    //     return view('requestproduct.edit', compact('contact'));
    // }

    // public function update(Request $request, $id)
    // {
    //     dd($request->all());
    // }

    public function destroy($id)
    {
        if (checkpermission('RequestProductController@destroy')) {
            /* Updating selected entry Flag to 1 for soft delete */
            ContactUs::where('id', $id)->update(['flag' => 1]);

            return redirect()->back()->with('danger', 'Entry Deleted successfully');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }
}
