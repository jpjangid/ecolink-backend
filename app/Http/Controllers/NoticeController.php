<?php

namespace App\Http\Controllers;

use App\Models\Notice;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Str;

class NoticeController extends Controller
{
    public function index()
    {
        if (checkpermission('NoticeController@index')) {
            $notices = Notice::all();
            return view('notices.index', compact('notices'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function update_status(Request $request)
    {
        /* Updating status of selected entry */
        $notice = Notice::find($request->notice_id);
        $notice->status   = $request->status == 1 ? 0 : 1;
        $notice->update();

        return response()->json(['message' => 'Notice status updated successfully.']);
    }

    public function edit($id)
    {
        if (checkpermission('NoticeController@edit')) {
            $notice = Notice::find($id);
            return view('notices.edit', compact('notice'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'         =>  'required',
            'start_date'    =>  'date',
            'end_date'      =>  'date|after:start_date',
            'status'        =>  'required'
        ],[
            'end_date.after' => 'Notice End Date Should be after Notice Start date'
        ]);

        $notice = Notice::find($id);

        /* Storing Image on local disk */
        $image = $notice->image;
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->extension();
            $file = $request->file('image');
            $fileNameString = (string) Str::uuid();
            $image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/notices/', $file, $image);
        }

        $notice->update([
            'title'      =>  $request->title,
            'message'    =>  $request->message,
            'image'      =>  $image,
            'alt'        =>  $request->alt,
            'url'        =>  $request->url,
            'start_date' =>  $request->start_date,
            'end_date'   =>  $request->end_date,
            'status'     =>  $request->status,
        ]);

        /* After successfull update of data redirecting to index page with message */
        return redirect('admin/notices')->with('success', 'Notice Updated Successfully');
    }
}
