<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    public function index()
    {
        if (request()->ajax()) {
            $categories1 = DB::table('categories')->select('id', 'name', 'slug', 'status')->where('flag', 0)->where('parent_id', null)->get();

            $categories = new Collection;
            foreach ($categories1 as $category1) {
                $categories->push([
                    'id'    => $category1->id,
                    'name'  => $category1->name,
                    'slug'  => $category1->slug,
                    'status' => $category1->status
                ]);
            }

            return Datatables::of($categories)
                ->addIndexColumn()
                ->addColumn('active', function ($row) {
                    $checked = $row['status'] == '1' ? 'checked' : '';
                    $active  = '<div class="form-check form-switch form-check-custom form-check-solid">
                                        <input type="hidden" value="' . $row['id'] . '" class="category_id">
                                        <input type="checkbox" class="form-check-input js-switch  h-20px w-30px" id="customSwitch1" name="status" value="' . $row['status'] . '" ' . $checked . '>
                                    </div>';

                    return $active;
                })
                ->addColumn('action', function ($row) {
                    $delete_url = url('admin/categories/delete', $row['id']);
                    $edit_url = url('admin/categories/edit', $row['id']);
                    $btn = '<a class="btn btn-primary btn-xs ml-1" href="' . $edit_url . '"><i class="fas fa-edit"></i></a>';
                    $btn .= '<a class="btn btn-danger btn-xs ml-1" href="' . $delete_url . '"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'active'])
                ->make(true);
        }
        return view('category.index');
    }

    public function create()
    {
        return view('category.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name'                  =>  'required|unique:categories,name',
            'description'           =>  'required',
            'alt'                   =>  'required',
            'slug'                  =>  'required|unique:categories,slug',
        ], [
            'name.required'                 =>  'Category Name is required',
            'description.required'          =>  'Category Description is required',
            'alt.required'                  =>  'Category Image Alt text is required',
            'slug.required'                 =>  'Category Slug is required',
        ]);

        $image = "";
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->extension();
            $file = $request->file('image');
            $fileNameString = (string) Str::uuid();
            $image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/category/', $file, $image);
        }

        $og_image = "";
        if ($request->hasFile('og_image')) {
            $extension = $request->file('og_image')->extension();
            $file = $request->file('og_image');
            $fileNameString = (string) Str::uuid();
            $og_image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/category/og_images', $file, $og_image);
        }

        $status = 0;
        if (isset($request->status)) {
            $status = 1;
        }

        Category::create([
            'slug'                      =>  $request->slug,
            'name'                      =>  $request->name,
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

        return redirect('admin/categories')->with('success', 'Category added successfully');
    }

    public function edit($id)
    {
        $category = DB::table('categories')->where('id', $id)->first();
        return view('category.edit', compact('category', 'id'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name'                  =>  'required|unique:categories,name,' . $id,
            'description'           =>  'required',
            'alt'                   =>  'required',
            'slug'                  =>  'required|unique:categories,slug,' . $id,
        ], [
            'name.required'                 =>  'Category Name is required',
            'description.required'          =>  'Category Description is required',
            'alt.required'                  =>  'Category Image Alt text is required',
            'slug.required'                 =>  'Category Slug is required',
        ]);

        $image = "";
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->extension();
            $file = $request->file('image');
            $fileNameString = (string) Str::uuid();
            $image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/category/', $file, $image);
        }

        $og_image = "";
        if ($request->hasFile('og_image')) {
            $extension = $request->file('og_image')->extension();
            $file = $request->file('og_image');
            $fileNameString = (string) Str::uuid();
            $og_image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/category/og_images', $file, $og_image);
        }

        $status = 0;
        if (isset($request->status)) {
            $status = 1;
        }

        DB::table('categories')->where('id', $id)->update([
            'slug'                      =>  $request->slug,
            'name'                      =>  $request->name,
            'description'               =>  $request->description,
            'image'                     =>  $image,
            'meta_title'                =>  $request->meta_title,
            'meta_description'          =>  $request->meta_description,
            'keywords'                  =>  $request->keywords,
            'alt'                       =>  $request->alt,
            'status'                    =>  $status,
            'og_title'                  =>  $request->og_title,
            'og_description'            =>  $request->og_description,
            'og_image'                  =>  $og_image,
        ]);

        return redirect('admin/categories')->with('success', 'Category updated successfully');
    }

    public function update_status(Request $request)
    {
        $category = Category::find($request->category_id);
        $category->status   = $request->status == 1 ? 0 : 1;
        $category->update();

        return response()->json(['message' => 'Category status updated successfully.']);
    }

    public function destroy($id)
    {
        DB::table('categories')->where('id', $id)->update(['flag' => 1]);
        return redirect('admin/categories')->with('danger', 'Category deleted successfully');
    }

    //level 2 code for sub category
    public function index_sub()
    {
        if (request()->ajax()) {
            $categories1 = Category::where('flag', 0)->where('parent_id', null)->with('subcategory')->distinct('parent_id')->get();
            $categories = new Collection;
            foreach ($categories1 as $category1) {
                if (!empty($category1->subcategory)) {
                    foreach ($category1->subcategory as $sub) {
                        if ($sub->flag == 0) {
                            $categories->push([
                                'id'    => $sub->id,
                                'main'  => $category1->name,
                                'name'  => $sub->name,
                                'slug'  => $sub->slug,
                                'status' => $sub->status
                            ]);
                        }
                    }
                }
            }

            return Datatables::of($categories)
                ->addIndexColumn()
                ->addColumn('active', function ($row) {
                    $checked = $row['status'] == '1' ? 'checked' : '';
                    $active  = '<div class="form-check form-switch form-check-custom form-check-solid">
                                        <input type="hidden" value="' . $row['id'] . '" class="category_id">
                                        <input type="checkbox" class="form-check-input js-switch  h-20px w-30px" id="customSwitch1" name="status" value="' . $row['status'] . '" ' . $checked . '>
                                    </div>';

                    return $active;
                })
                ->addColumn('action', function ($row) {
                    $delete_url = url('admin/sub/categories/delete', $row['id']);
                    $edit_url = url('admin/sub/categories/edit', $row['id']);
                    $btn = '<a class="btn btn-primary btn-xs ml-1" href="' . $edit_url . '"><i class="fas fa-edit"></i></a>';
                    $btn .= '<a class="btn btn-danger btn-xs ml-1" href="' . $delete_url . '"><i class="fa fa-trash"></i></a>';
                    return $btn;
                })
                ->rawColumns(['action', 'active'])
                ->make(true);
        }
        return view('category.sub.index');
    }

    public function create_sub()
    {
        $categories = DB::table('categories')->where('parent_id', null)->where(['flag' => 0, 'status' => 1])->get();
        return view('category.sub.create', compact('categories'));
    }

    public function store_sub(Request $request)
    {
        $request->validate([
            'name'                  =>  'required|unique:categories,name',
            'description'           =>  'required',
            'alt'                   =>  'required',
            'slug'                  =>  'required|unique:categories,slug',
        ], [
            'name.required'                 =>  'Category Name is required',
            'description.required'          =>  'Category Description is required',
            'alt.required'                  =>  'Category Image Alt text is required',
            'slug.required'                 =>  'Category Slug is required',
        ]);

        $image = "";
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->extension();
            $file = $request->file('image');
            $fileNameString = (string) Str::uuid();
            $image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/category/', $file, $image);
        }

        $og_image = "";
        if ($request->hasFile('og_image')) {
            $extension = $request->file('og_image')->extension();
            $file = $request->file('og_image');
            $fileNameString = (string) Str::uuid();
            $og_image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/category/og_images', $file, $og_image);
        }

        $status = 0;
        if (isset($request->status)) {
            $status = 1;
        }

        Category::create([
            'slug'                      =>  $request->slug,
            'name'                      =>  $request->name,
            'parent_id'                 =>  $request->parent_id,
            'description'               =>  $request->description,
            'image'                     =>  $image,
            'meta_title'                =>  $request->meta_title,
            'meta_description'          =>  $request->meta_description,
            'keywords'                  =>  $request->keywords,
            'alt'                       =>  $request->alt,
            'status'                    =>  $status,
            'og_title'                  =>  $request->og_title,
            'og_description'            =>  $request->og_description,
            'og_image'                  =>  $og_image,
        ]);

        return redirect('admin/sub/categories')->with('success', 'Sub category added successfully');
    }

    public function edit_sub($id)
    {
        $categories = DB::table('categories')->where('parent_id', null)->where(['flag' => 0, 'status' => 1])->get();
        $category = DB::table('categories')->where('id', $id)->first();
        return view('category.sub.edit', compact('category', 'categories', 'id'));
    }

    public function update_sub(Request $request, $id)
    {
        $request->validate([
            'name'                  =>  'required|unique:categories,name,' . $id,
            'description'           =>  'required',
            'alt'                   =>  'required',
            'slug'                  =>  'required|unique:categories,slug,' . $id,
        ], [
            'name.required'                 =>  'Category Name is required',
            'description.required'          =>  'Category Description is required',
            'alt.required'                  =>  'Category Image Alt text is required',
            'slug.required'                 =>  'Category Slug is required',
        ]);

        $image = "";
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->extension();
            $file = $request->file('image');
            $fileNameString = (string) Str::uuid();
            $image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/category/', $file, $image);
        }

        $og_image = "";
        if ($request->hasFile('og_image')) {
            $extension = $request->file('og_image')->extension();
            $file = $request->file('og_image');
            $fileNameString = (string) Str::uuid();
            $og_image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/category/og_images', $file, $og_image);
        }

        $status = 0;
        if (isset($request->status)) {
            $status = 1;
        }

        DB::table('categories')->where('id', $id)->update([
            'slug'                      =>  $request->slug,
            'name'                      =>  $request->name,
            'parent_id'                 =>  $request->parent_id,
            'description'               =>  $request->description,
            'image'                     =>  $image,
            'meta_title'                =>  $request->meta_title,
            'meta_description'          =>  $request->meta_description,
            'keywords'                  =>  $request->keywords,
            'alt'                       =>  $request->alt,
            'status'                    =>  $status,
            'og_title'                  =>  $request->og_title,
            'og_description'            =>  $request->og_description,
            'og_image'                  =>  $og_image,
        ]);

        return redirect('admin/sub/categories')->with('success', 'Sub category updated successfully');
    }

    public function update_status_sub(Request $request)
    {
        $category = Category::find($request->category_id);
        $category->status   = $request->status == 1 ? 0 : 1;
        $category->update();

        return response()->json(['message' => 'Sub category status updated successfully.']);
    }

    public function destroy_sub($id)
    {
        DB::table('categories')->where('id', $id)->update(['flag' => 1]);
        return redirect('admin/sub/categories')->with('danger', 'Sub Category deleted successfully');
    }
}
