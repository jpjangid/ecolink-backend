<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Page;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class PageController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $pages1 = DB::table('pages')->where(['flag' => '0', 'status' => 1])->get();

            $pages = new Collection;
            foreach ($pages1 as $page) {
                $pages->push([
                    'id'    => $page->id,
                    'title'  => $page->title,
                    'slug'  => $page->slug,
                    'status' => $page->status
                ]);
            }

            return Datatables::of($pages)
                ->addIndexColumn()
                ->addColumn('active', function ($row) {
                    $checked = $row['status'] == '1' ? 'checked' : '';
                    $active  = '<div class="form-check form-switch form-check-custom form-check-solid">
                                        <input type="hidden" value="' . $row['id'] . '" class="page_id">
                                        <input type="checkbox" class="form-check-input js-switch  h-20px w-30px" id="customSwitch1" name="status" value="' . $row['status'] . '" ' . $checked . '>
                                    </div>';

                    return $active;
                })
                ->addColumn('action', function ($row) {
                    $delete_url = url('admin/pages/delete', $row['id']);
                    $edit_url = url('admin/pages/edit', $row['id']);
                    $btn = '<a class="btn btn-primary btn-xs ml-1" href="' . $edit_url . '"><i class="fas fa-edit"></i></a>';
                    $btn .= '<a class="btn btn-danger btn-xs ml-1" href="' . $delete_url . '"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'active'])
                ->make(true);
        }

        return view('pages.index');
    }

    public function create()
    {
        return view('pages.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'                 =>  'required',
            'description'           =>  'required',
            'slug'                  =>  'required',
        ], [
            'title.required'                =>  'Page Title is required',
            'description.required'          =>  'Page Description is required',
            'slug.required'                 =>  'Page Slug is required',
        ]);

        $image = "";
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->extension();
            $file = $request->file('image');
            $fileNameString = (string) Str::uuid();
            $image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/pages/', $file, $image);
        }

        $og_image = "";
        if ($request->hasFile('og_image')) {
            $extension = $request->file('og_image')->extension();
            $file = $request->file('og_image');
            $fileNameString = (string) Str::uuid();
            $og_image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/pages/og_images', $file, $og_image);
        }

        $status = 0;
        if (isset($request->status)) {
            $status = 1;
        }

        Page::create([
            'slug'                      =>  $request->slug,
            'title'                     =>  $request->title,
            'description'               =>  $request->description,
            'image'                     =>  $image,
            'meta_title'                =>  $request->meta_title,
            'meta_description'          =>  $request->meta_description,
            'keywords'                  =>  $request->keywords,
            'tags'                      =>  $request->tags,
            'alt'                       =>  $request->alt,
            'status'                    =>  $status,
            'og_title'                  =>  $request->og_title,
            'og_description'            =>  $request->og_description,
            'og_image'                  =>  $og_image,
        ]);

        return redirect('admin/pages')->with('success', 'Page Created Successfully');
    }

    public function update_status(Request $request)
    {
        $page = Page::find($request->page_id);
        $page->status   = $request->status == 1 ? 0 : 1;
        $page->update();

        return response()->json(['message' => 'Page status updated successfully.']);
    }

    public function edit($id)
    {
        $page = DB::table('pages')->find($id);

        return view('pages.edit', compact('page', 'id'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'title'                 =>  'required',
            'description'           =>  'required',
            'slug'                  =>  'required',
        ], [
            'title.required'                =>  'Page Title is required',
            'description.required'          =>  'Page Description is required',
            'slug'                          =>  'Page Slug is required',
        ]);

        $page = Page::find($id);

        $image = $page->image;
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->extension();
            $file = $request->file('image');
            $fileNameString = (string) Str::uuid();
            $image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/pages/', $file, $image);
        }

        $og_image = $page->og_image;
        if ($request->hasFile('og_image')) {
            $extension = $request->file('og_image')->extension();
            $file = $request->file('og_image');
            $fileNameString = (string) Str::uuid();
            $og_image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/pages/og_images', $file, $og_image);
        }

        $status = 0;
        if (isset($request->status)) {
            $status = 1;
        }

        $page->slug                     =  $request->slug;
        $page->title                    =  $request->title;
        $page->description              =  $request->description;
        $page->image                    =  $image;
        $page->meta_title               =  $request->meta_title;
        $page->meta_description         =  $request->meta_description;
        $page->keywords                 =  $request->keywords;
        $page->tags                     =  $request->tags;
        $page->alt                      =  $request->alt;
        $page->status                   =  $status;
        $page->og_title                 =  $request->og_title;
        $page->og_description           =  $request->og_description;
        $page->og_image                 =  $og_image;
        $page->update();

        return redirect('admin/pages')->with('success', 'Page Updated Successfully');
    }

    public function destroy($id)
    {
        $page = Page::find($id);
        $page->flag   =   '1';
        $page->update();

        return redirect('admin/pages')->with('danger', 'Page Deleted');
    }
}
