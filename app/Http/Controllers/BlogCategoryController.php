<?php

namespace App\Http\Controllers;

use App\Models\BlogCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

class BlogCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (checkpermission('BlogCategoryController@index')) {
            if (request()->ajax()) {
                /* Getting all records */
                $allblogcategories = BlogCategory::where('flag', '0')->orderby('created_at', 'desc')->get();
                /* Converting Selected Data into desired format */
                $blogCategories = new Collection;
                foreach ($allblogcategories as $allblogcategory) {
                    $blogCategories->push([
                        'id'                => $allblogcategory->id,
                        'blog_category'     => $allblogcategory->blog_category,
                    ]);
                }
                /* Sending data through yajra datatable for server side rendering */
                return Datatables::of($blogCategories)
                    ->addIndexColumn()
                    /* Adding Actions like edit, delete and show */
                    ->addColumn('action', function ($row) {
                        $delete_url = url('admin/blogcategory/delete', $row['id']);
                        $edit_url = url('admin/blogcategory/edit', $row['id']);
                        $btn = '
                            <div style="display:flex;">
                            <a class="btn btn-primary btn-xs" href="' . $edit_url . '" style="margin-right: 2px;"><i class="fas fa-edit"></i></a>
                                <form action="' . $delete_url . '" method="post">
                                    <input type="hidden" name="_token" value="' . csrf_token() . '">
                                    <input type="hidden" name="_method" value="DELETE">
                                    <button class="delete btn btn-danger btn-xs blog_categrory_confirm"><i class="fas fa-trash"></i></button>
                                </form>
                            </div>';
                        return $btn;
                    })
                    ->rawColumns(['action'])
                    ->make(true);
            }
            return view('blogcategory.index');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function create()
    {
        return view('blogcategory.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'blog_category'     => 'required',
        ], [
            'blog_category.required'     => 'Please Enter Blog Category',
        ]);
        BlogCategory::create([
            'blog_category'     => $request->blog_category,
        ]);
        return redirect('admin/blogcategory')->with('success', 'Blog Category Create Successfully');
    }

    public function edit($id)
    {
        if (checkpermission('BlogCategoryController@edit')) {
            $blogcategory = BlogCategory::find($id);
            return view('blogcategory.edit', compact('blogcategory'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'blog_category'     => 'required',
        ], [
            'blog_category.required'     => 'Please Enter Blog Category',
        ]);
        $blogcategory =  BlogCategory::find($id);
        $blogcategory->blog_category  = $request->blog_category;
        $blogcategory->update();
        return view('blogcategory.index')->with('success', 'Blog Category Update Successfully');
    }

    public function destroy($id)
    {
        if (checkpermission('BlogCategoryController@destroy')) {
            /* Updating selected entry Flag to 1 for soft delete */
            BlogCategory::where('id', $id)->update(['flag' => 1, 'status' => 0]);

            return redirect('admin/blogcategory')->with('danger', 'Blog Deleted');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }
}
