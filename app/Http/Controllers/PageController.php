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
            /* Getting all records */
            $allpages = DB::table('pages')->select('id', 'title', 'slug', 'status')->where(['flag' => '0', 'status' => 1])->get();

            /* Converting Selected Data into desired format */
            $pages = new Collection;
            foreach ($allpages as $page) {
                $pages->push([
                    'id'        => $page->id,
                    'title'     => $page->title,
                    'slug'      => $page->slug,
                    'status'    => $page->status
                ]);
            }

            /* Sending data through yajra datatable for server side rendering */
            return Datatables::of($pages)
                ->addIndexColumn()
                /* Status Active and Deactive Checkbox */
                ->addColumn('active', function ($row) {
                    $checked = $row['status'] == '1' ? 'checked' : '';
                    $active  = '<div class="form-check form-switch form-check-custom form-check-solid">
                                        <input type="hidden" value="' . $row['id'] . '" class="page_id">
                                        <input type="checkbox" class="form-check-input js-switch  h-20px w-30px" id="customSwitch1" name="status" value="' . $row['status'] . '" ' . $checked . '>
                                    </div>';

                    return $active;
                })
                /* Adding Actions like edit, delete and show */
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
        /* Loading Create Page */
        return view('pages.create');
    }

    public function store(Request $request)
    {
        /* Validating Input fields */
        $request->validate([
            'title'                 =>  'required',
            'description'           =>  'required',
            'slug'                  =>  'required',
        ], [
            'title.required'                =>  'Page Title is required',
            'description.required'          =>  'Page Description is required',
            'slug.required'                 =>  'Page Slug is required',
        ]);

        /* Storing Featured Image on local disk */
        $image = "";
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->extension();
            $file = $request->file('image');
            $fileNameString = (string) Str::uuid();
            $image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/pages/', $file, $image);
        }

        /* Storing OG Image on local disk */
        $og_image = "";
        if ($request->hasFile('og_image')) {
            $extension = $request->file('og_image')->extension();
            $file = $request->file('og_image');
            $fileNameString = (string) Str::uuid();
            $og_image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/pages/og_images', $file, $og_image);
        }

        /* Storing Data in Table */
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
            'status'                    =>  $request->status,
            'og_title'                  =>  $request->og_title,
            'og_description'            =>  $request->og_description,
            'og_image'                  =>  $og_image,
            'category'                  =>  $request->category,
        ]);

        /* After Successfull insertion of data redirecting to listing page with message */
        return redirect('admin/pages')->with('success', 'Page Created Successfully');
    }

    public function update_status(Request $request)
    {
        /* Updating status of selected entry */
        $page = Page::find($request->page_id);
        $page->status   = $request->status == 1 ? 0 : 1;
        $page->update();

        return response()->json(['message' => 'Page status updated successfully.']);
    }

    public function edit($id)
    {
        /* Getting Blog data for edit using Id */
        $page = DB::table('pages')->find($id);

        return view('pages.edit', compact('page', 'id'));
    }

    public function update(Request $request, $id)
    {
        /* Validating Input fields */
        $request->validate([
            'title'                 =>  'required',
            'description'           =>  'required',
            'slug'                  =>  'required',
        ], [
            'title.required'                =>  'Page Title is required',
            'description.required'          =>  'Page Description is required',
            'slug'                          =>  'Page Slug is required',
        ]);

        /* Fetching Blog Data using Id */
        $page = Page::find($id);

        /* Storing Featured Image on local disk */
        $image = $page->image;
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->extension();
            $file = $request->file('image');
            $fileNameString = (string) Str::uuid();
            $image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/pages/', $file, $image);
        }

        /* Storing OG Image on local disk */
        $og_image = $page->og_image;
        if ($request->hasFile('og_image')) {
            $extension = $request->file('og_image')->extension();
            $file = $request->file('og_image');
            $fileNameString = (string) Str::uuid();
            $og_image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/pages/og_images', $file, $og_image);
        }

        /* Updating Data fetched by Id */
        $page->slug                     =  $request->slug;
        $page->title                    =  $request->title;
        $page->description              =  $request->description;
        $page->image                    =  $image;
        $page->meta_title               =  $request->meta_title;
        $page->meta_description         =  $request->meta_description;
        $page->keywords                 =  $request->keywords;
        $page->tags                     =  $request->tags;
        $page->alt                      =  $request->alt;
        $page->status                   =  $request->status;
        $page->og_title                 =  $request->og_title;
        $page->og_description           =  $request->og_description;
        $page->og_image                 =  $og_image;
        $page->category                 =  $request->category;
        $page->update();

        /* After successfull update of data redirecting to index page with message */
        return redirect('admin/pages')->with('success', 'Page Updated Successfully');
    }

    public function destroy($id)
    {
        /* Updating selected entry Flag to 1 for soft delete */
        Page::where('id', $id)->update(['flag' => 1]);

        return redirect('admin/pages')->with('danger', 'Page Deleted');
    }
}
