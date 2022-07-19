<?php

namespace App\Http\Controllers;

use App\Models\NewsLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Mail;
use App\Mail\Subscribe;

class NewsLetterController extends Controller
{
    public function index()
    {
        if (checkpermission('NewsLetterController@index')) {
            if (request()->ajax()) {
                /* Getting all records */
                $allnewsletters = DB::table('news_letters')->select('id', 'email', 'created_at')->where('flag', '0')->orderby('created_at','desc')->get();

                /* Converting Selected Data into desired format */
                $newsletters = new Collection;
                foreach ($allnewsletters as $newsletter) {
                    $newsletters->push([
                        'id'        => $newsletter->id,
                        'email'     => $newsletter->email,
                        'date'      => date('d-m-Y H:i', strtotime($newsletter->created_at)),
                    ]);
                }

                /* Sending data through yajra datatable for server side rendering */
                return Datatables::of($newsletters)
                    ->addIndexColumn()
                    /* Adding Actions like edit, delete and show */
                    ->addColumn('action', function ($row) {
                        $delete_url = url('admin/newsletters/delete', $row['id']);
                        $edit_url = url('admin/newsletters/edit', $row['id']);
                        $btn = '
                        <div style="display:flex;">
                        <a class="btn btn-primary btn-xs" href="' . $edit_url . '" style="margin-right: 2px;"><i class="fas fa-edit"></i></a>
                                        <form action="' . $delete_url . '" method="post">
                                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="delete btn btn-danger btn-xs newsletter_confirm"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('newsletters.index');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function create()
    {
        if (checkpermission('NewsLetterController@create')) {
            /* Loading Create Page */
            return view('newsletters.create');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function store(Request $request)
    {
        /* Validating Input fields */
        $request->validate([
            'email'     =>  'required|email|unique:news_letters,email',
        ]);

        /* Storing Data in Table */
        NewsLetter::create([
            'email'     =>  $request->email,
        ]);
        Mail::to($request->email)->cc("mohsinkhan6992@gmail.com")->send(
            new Subscribe(
                $request->email,
            )
        );
        /* After Successfull insertion of data redirecting to listing page with message */
        return redirect('admin/newsletters')->with('success', 'News Letter Added successfully');
    }

    public function edit($id)
    {
        if (checkpermission('NewsLetterController@edit')) {
            /* Getting News Letter data for edit using Id */
            $letter = DB::table('news_letters')->find($id);
            return view('newsletters.edit', compact('id', 'letter'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function update(Request $request, $id)
    {
        /* Validating Input fields */
        $request->validate([
            'email'     =>  'required|email',
        ]);

        /* Updating Data fetched by Id */
        $letter = NewsLetter::find($id);
        $letter->email = $request->email;
        $letter->update();

        /* After successfull update of data redirecting to index page with message */
        return redirect('admin/newsletters')->with('success', 'News Letter Updated successfully');
    }

    public function destroy($id)
    {
        if (checkpermission('NewsLetterController@destroy')) {
            /* Updating selected entry Flag to 1 for soft delete */
            NewsLetter::where('id', $id)->update(['flag' => 1]);

            return redirect('admin/newsletters')->with('danger', 'News Letter Deleted successfully');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }
}
