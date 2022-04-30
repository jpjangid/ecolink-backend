<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\ContactUs;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;


class AskChemistController extends Controller
{
    public function index()
    {
        if (checkpermission('AskChemistController@index')) {
            if (request()->ajax()) {
                /* Getting all records */
                $allcontact = DB::table('contact_us')->select('id', 'first_name', 'last_name', 'phone', 'email', 'created_at')->where(['flag' => '0', 'type' => 'askchemist'])->get();

                /* Converting Selected Data into desired format */
                $contacts = new Collection;
                foreach ($allcontact as $contact) {
                    $contacts->push([
                        'id'            => $contact->id,
                        'first_name'    => $contact->first_name,
                        'last_name'     => $contact->last_name,
                        'phone'         => $contact->phone,
                        'email'         => $contact->email,
                        'created_at'    => date('d-m-Y', strtotime($contact->created_at)),
                    ]);
                }

                /* Sending data through yajra datatable for server side rendering */
                return Datatables::of($contacts)
                    ->addIndexColumn()
                    /* Adding Actions like edit, delete and show */
                    ->addColumn('action', function ($row) {
                        $delete_url = url('admin/askchemist/delete', $row['id']);
                        $edit_url = url('admin/askchemist/edit', $row['id']);
                        $show_url = url('admin/askchemist/show', $row['id']);
                        $btn = '';
                        // $btn = '<a class="btn btn-primary btn-xs ml-1" href="' . $edit_url . '"><i class="fas fa-edit"></i></a>';
                        $btn .= '<a class="btn btn-info btn-xs ml-1" href="' . $show_url . '"><i class="fa fa-eye"></i></a>';
                        $btn .= '<a class="btn btn-danger btn-xs ml-1" href="' . $delete_url . '"><i class="fa fa-trash"></i></a>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('askchemist.index');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function show($id)
    {
        if (checkpermission('AskChemistController@edit')) {
            /* Getting Contact with Question to show using Id */
            $contact = ContactUs::where('id', $id)->with('question')->first();
            return view('askchemist.show', compact('contact'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    // public function edit($id)
    // {
    //     $contact = ContactUs::where('id', $id)->with('question')->first();

    //     return view('askchemist.edit', compact('contact'));
    // }

    // public function update(Request $request, $id)
    // {
    //     dd($request->all());
    // }

    public function destroy($id)
    {
        if (checkpermission('AskChemistController@destroy')) {
            /* Updating selected entry Flag to 1 for soft delete */
            ContactUs::where('id', $id)->update(['flag' => 1]);

            return redirect()->back()->with('danger', 'Entry Deleted successfully');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }
}
