<?php

namespace App\Http\Controllers;

use App\Models\NewsLetter;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class NewsLetterController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $letters1 = DB::table('news_letters')->where('flag', '0')->get();

            $letters = new Collection;
            foreach ($letters1 as $letter) {
                $letters->push([
                    'id'        => $letter->id,
                    'email'     => $letter->email,
                    'date'      => date('d-m-Y', strtotime($letter->created_at)),
                ]);
            }

            return Datatables::of($letters)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $delete_url = url('admin/newsletters/delete', $row['id']);
                    $edit_url = url('admin/newsletters/edit', $row['id']);
                    $btn = '<a class="btn btn-primary btn-xs ml-1" href="' . $edit_url . '"><i class="fas fa-edit"></i></a>';
                    $btn .= '<a class="btn btn-danger btn-xs ml-1" href="' . $delete_url . '"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action'])
                ->make(true);
        }
        return view('newsletters.index');
    }

    public function create()
    {
        return view('newsletters.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'email'     =>  'required|email',
        ]);

        NewsLetter::create([
            'email'     =>  $request->email,
        ]);

        return redirect('admin/newsletters')->with('success', 'News Letter Added successfully');
    }

    public function edit($id)
    {
        $letter = DB::table('news_letters')->find($id);
        return view('newsletters.edit', compact('id', 'letter'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'email'     =>  'required|email',
        ]);

        $letter = NewsLetter::find($id);
        $letter->email = $request->email;
        $letter->update();

        return redirect('admin/newsletters')->with('success', 'News Letter Updated successfully');
    }

    public function destroy($id)
    {
        $letter = NewsLetter::find($id);
        $letter->flag = 1;
        $letter->update();

        return redirect('admin/newsletters')->with('danger', 'News Letter Deleted successfully');
    }
}
