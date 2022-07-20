<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;
use App\Models\BlogCategory;

class BlogController extends Controller
{
    public function index(Request $request)
    {
        if (checkpermission('BlogController@index')) {
            if (request()->ajax()) {
                /* Getting all records */
                $active = $request->active == 'all' ? array('1', '2', '0') : array($request->active);
                $allblogs = DB::table('blogs')->select('id', 'title', 'slug', 'category', 'publish_date', 'status')->where('flag', '0')->whereIn('status', $active)->orderby('created_at', 'desc')->get();

                /* Converting Selected Data into desired format */
                $blogs = new Collection;
                foreach ($allblogs as $blog) {
                    if ($blog->status == 1) {
                        $status = 'Publish';
                    } elseif ($blog->status == 0) {
                        $status = 'Draft';
                    } else {
                        $status = 'Schedule';
                    }
                    $blogs->push([
                        'id'                => $blog->id,
                        'title'             => $blog->title,
                        'slug'              => $blog->slug,
                        'category'          => $blog->category,
                        'publish_date'      => date('m-d-Y', strtotime($blog->publish_date)),
                        'status'            => $blog->status,
                        'publish_blog'      => $status
                    ]);
                }

                /* Sending data through yajra datatable for server side rendering */
                return Datatables::of($blogs)
                    ->addIndexColumn()
                    /* Status Active and Deactivated Checkbox */
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
                        $btn = '
                        <div style="display:flex;">
                        <a class="btn btn-primary btn-xs" href="' . $edit_url . '" style="margin-right: 2px;"><i class="fas fa-edit"></i></a>
                                        <form action="' . $delete_url . '" method="post">
                                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="delete btn btn-danger btn-xs blog_confirm"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'active'])
                    ->make(true);
            }
            return view('blogs.index');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function create()
    {
        if (checkpermission('BlogController@create')) {
            /* Loading Create Page */
            $blogcategories = BlogCategory::where('flag', '0')->get();
            return view('blogs.create', compact('blogcategories'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
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
            'status'                =>  'required',
            'image'                 =>  'required',
            'category'              =>  'required'
        ], [
            'title.required'                =>  'Blog Title is required',
            'description.required'          =>  'Blog Description is required',
            'publish_date.required'         =>  'Blog Publish Date is required',
            'alt.required'                  =>  'Featured Image Alt text is required',
            'slug.required'                 =>  'Blog Slug is required',
            'status.required'               =>  'Blog Status is required',
            'image.required'                =>  'Blog Image is required',
            'category.required'             =>  'Blog Category is required',
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
            'short_desc'                =>  $request->short_desc,
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
            'head_schema'               =>  $request->head_schema,
            'body_schema'               =>  $request->body_schema,
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
        if ($blog->status == 1) {
            $data['msg'] = 'success';
            return response()->json($data);
        } else {
            $data['msg'] = 'danger';
            return response()->json($data);
        }
    }

    public function edit($id)
    {
        if (checkpermission('BlogController@edit')) {
            /* Getting Blog data for edit using Id */
            $blog = DB::table('blogs')->find($id);
            $blogcategories = BlogCategory::where('flag', '0')->get();
            return view('blogs.edit', compact('blog', 'id', 'blogcategories'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
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
            'status'                =>  'required',
            'category'              =>  'required'
        ], [
            'title.required'                =>  'Blog Title is required',
            'description.required'          =>  'Blog Description is required',
            'publish_date.required'         =>  'Blog Publish Date is required',
            'alt.required'                  =>  'Featured Image Alt text is required',
            'slug.required'                 =>  'Blog Slug is required',
            'status.required'               =>  'Blog Status is required',
            'category.required'             =>  'Blog Category is required',
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
        $blog->short_desc               =  $request->short_desc;
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
        $blog->head_schema              =  $request->head_schema;
        $blog->body_schema              =  $request->body_schema;
        $blog->update();

        /* After successfull update of data redirecting to index page with message */
        return redirect('admin/blogs')->with('success', 'Blog Updated Successfully');
    }

    public function destroy($id)
    {
        if (checkpermission('BlogController@destroy')) {
            /* Updating selected entry Flag to 1 for soft delete */
            Blog::where('id', $id)->update(['flag' => 1, 'status' => 0]);

            return redirect('admin/blogs')->with('danger', 'Blog Deleted');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }
}
