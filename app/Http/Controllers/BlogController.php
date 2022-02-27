<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class BlogController extends

Controller
{
    public function index()
    {
        if (request()->ajax()) {
            /* Getting all records */
            $allblogs = DB::table('blogs')->select('id', 'title', 'slug', 'category', 'publish_date', 'status')->where('flag', '0')->get();

            /* Converting Selected Data into desired format */
            $blogs = new Collection;
            foreach ($allblogs as $blog) {
                $blogs->push([
                    'id'            => $blog->id,
                    'title'         => $blog->title,
                    'slug'          => $blog->slug,
                    'category'      => $blog->category,
                    'publish_date'  => date('d-m-Y', strtotime($blog->publish_date)),
                    'status' => $blog->status
                ]);
            }

            /* Sending data through yajra datatable for server side rendering */
            return Datatables::of($blogs)
                ->addIndexColumn()
                /* Status Active and Deactive Checkbox */
                ->addColumn('active', function ($row) {
                    $checked = $row['status'] == '1' ? 'checked' : '';
                    $active  = '<div class="form-check form-switch form-check-custom form-check-solid">
                                        <input type="hidden" value="' . $row['id'] . '" class="blog_id">
                                        <input type="checkbox" class="form-check-input js-switch  h-20px w-30px" id="customSwitch1" name="status" value="' . $row['status'] . '" ' . $checked . '>
                                    </div>';

                    return $active;
                })
                /* Adding Actions like edit, delete and show */
                ->addColumn('action', function ($row) {
                    $delete_url = url('admin/blogs/delete', $row['id']);
                    $edit_url = url('admin/blogs/edit', $row['id']);
                    $btn = '<a class="btn btn-primary btn-xs ml-1" href="' . $edit_url . '"><i class="fas fa-edit"></i></a>';
                    $btn .= '<a class="btn btn-danger btn-xs ml-1" href="' . $delete_url . '"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'active'])
                ->make(true);
        }
        return view('blogs.index');
    }

    public function create()
    {
        /* Loading Create Page */
        return view('blogs.create');
    }

    public function store(Request $request)
    {
        /* Validating Input fields */
        $request->validate([
            'title'                 =>  'required',
            'description'           =>  'required',
            'publish_date'          =>  'required',
            'alt'                   =>  'required',
            'slug'                  =>  'required',
        ], [
            'title.required'                =>  'Blog Title is required',
            'description.required'          =>  'Blog Description is required',
            'publish_date.required'         =>  'Blog Publish Date is required',
            'alt.required'                  =>  'Featured Image Alt text is required',
            'slug.required'                 =>  'Blog Slug is required',
        ]);

        /* Storing Featured Image on local disk */
        $image = "";
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->extension();
            $file = $request->file('image');
            $fileNameString = (string) Str::uuid();
            $image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/blogs/', $file, $image);
        }

        /* Storing OG Image on local disk */
        $og_image = "";
        if ($request->hasFile('og_image')) {
            $extension = $request->file('og_image')->extension();
            $file = $request->file('og_image');
            $fileNameString = (string) Str::uuid();
            $og_image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/blogs/og_images', $file, $og_image);
        }

        /* Storing Data in Table */
        Blog::create([
            'slug'                      =>  $request->slug,
            'title'                     =>  $request->title,
            'description'               =>  $request->description,
            'image'                     =>  $image,
            'meta_title'                =>  $request->meta_title,
            'meta_description'          =>  $request->meta_description,
            'keywords'                  =>  $request->keywords,
            'tags'                      =>  $request->tags,
            'publish_date'              =>  $request->publish_date,
            'alt'                       =>  $request->alt,
            'status'                    =>  $request->status,
            'category'                  =>  $request->category,
            'og_title'                  =>  $request->og_title,
            'og_description'            =>  $request->og_description,
            'og_image'                  =>  $og_image,
        ]);

        /* After Successfull insertion of data redirecting to listing page with message */
        return redirect('admin/blogs')->with('success', 'Blog Created Successfully');
    }

    public function update_status(Request $request)
    {
        /* Updating status of selected entry */
        $blog = Blog::find($request->blog_id);
        $blog->status   = $request->status == 1 ? 0 : 1;
        $blog->update();

        return response()->json(['message' => 'Blog status updated successfully.']);
    }

    public function edit($id)
    {
        /* Getting Blog data for edit using Id */
        $blog = DB::table('blogs')->find($id);

        return view('blogs.edit', compact('blog', 'id'));
    }

    public function update(Request $request, $id)
    {
        /* Validating Input fields */
        $request->validate([
            'title'                 =>  'required',
            'description'           =>  'required',
            'publish_date'          =>  'required',
            'alt'                   =>  'required',
            'slug'                  =>  'required',
        ], [
            'title.required'                =>  'Blog Title is required',
            'description.required'          =>  'Blog Description is required',
            'publish_date.required'         =>  'Blog Publish Date is required',
            'alt.required'                  =>  'Featured Image Alt text is required',
            'slug'                          =>  'Blog Slug is required',
        ]);

        /* Fetching Blog Data using Id */
        $blog = Blog::find($id);

        /* Storing Featured Image on local disk */
        $image = $blog->image;
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->extension();
            $file = $request->file('image');
            $fileNameString = (string) Str::uuid();
            $image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/blogs/', $file, $image);
        }

        /* Storing OG Image on local disk */
        $og_image = $blog->og_image;
        if ($request->hasFile('og_image')) {
            $extension = $request->file('og_image')->extension();
            $file = $request->file('og_image');
            $fileNameString = (string) Str::uuid();
            $og_image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/blogs/og_images', $file, $og_image);
        }

        /* Updating Data fetched by Id */
        $blog->slug                     =  $request->slug;
        $blog->title                    =  $request->title;
        $blog->description              =  $request->description;
        $blog->image                    =  $image;
        $blog->meta_title               =  $request->meta_title;
        $blog->meta_description         =  $request->meta_description;
        $blog->keywords                 =  $request->keywords;
        $blog->tags                     =  $request->tags;
        $blog->publish_date             =  $request->publish_date;
        $blog->alt                      =  $request->alt;
        $blog->status                   =  $request->status;
        $blog->category                 =  $request->category;
        $blog->og_title                 =  $request->og_title;
        $blog->og_description           =  $request->og_description;
        $blog->og_image                 =  $og_image;
        $blog->update();

        /* After successfull update of data redirecting to index page with message */
        return redirect('admin/blogs')->with('success', 'Blog Updated Successfully');
    }

    public function destroy($id)
    {
        /* Updating selected entry Flag to 1 for soft delete */
        Blog::where('id', $id)->update(['flag' => 1]);

        return redirect('admin/blogs')->with('danger', 'Blog Deleted');
    }
}
