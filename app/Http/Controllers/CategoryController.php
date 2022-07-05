<?php

namespace App\Http\Controllers;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Symfony\Component\VarDumper\Cloner\Data;

class CategoryController extends Controller
{
    //Code for Main Category
    public function index(Request $request)
    {
        if (checkpermission('CategoryController@index')) {
            if (request()->ajax()) {
                /* Getting all records */
                $active = $request->active == 'all' ? array('1','2','0') : array($request->active);
                $maincategories = DB::table('categories')->select('id', 'name', 'slug', 'status')->where('flag', 0)->where('parent_id', null)->whereIn('status', $active)->orderby('created_at','desc')->get();

                /* Converting Selected Data into desired format */
                $categories = new Collection;
                foreach ($maincategories as $category1) {
                    $categories->push([
                        'id'        => $category1->id,
                        'name'      => $category1->name,
                        'slug'      => $category1->slug,
                        'status'    => $category1->status
                    ]);
                }

                /* Sending data through yajra datatable for server side rendering */
                return Datatables::of($categories)
                    ->addIndexColumn()
                    /* Status Active and Deactivated Checkbox */
                    ->addColumn('active', function ($row) {
                        $checked = $row['status'] == '1' ? 'checked' : '';
                        $active  = '<div class="form-check form-switch form-check-custom form-check-solid">
                                        <input type="hidden" value="' . $row['id'] . '" class="category_id">
                                        <input type="checkbox" class="form-check-input js-switch  h-20px w-30px" id="customSwitch1" name="status" value="' . $row['status'] . '" ' . $checked . '>
                                    </div>';

                        return $active;
                    })
                    /* Adding Actions like edit, delete and show */
                    ->addColumn('action', function ($row) {
                        $delete_url = url('admin/categories/delete', $row['id']);
                        $edit_url = url('admin/categories/edit', $row['id']);
                        $btn = '
                        <div style="display:flex;">
                        <a class="btn btn-primary btn-xs" href="' . $edit_url . '" style="margin-right: 2px;"><i class="fas fa-edit"></i></a>
                                        <form action="' . $delete_url . '" method="post">
                                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="delete btn btn-danger btn-xs category_confirm"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'active'])
                    ->make(true);
            }
            $settings = Setting::all();
            return view('category.index',compact('settings'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function create()
    {
        if (checkpermission('CategoryController@create')) {
            /* Loading Create Page */
            return view('category.create');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function store(Request $request)
    {
        /* Validating Input fields */
        $request->validate([
            'name'                  =>  'required|regex:/^[\pL\s\-]+$/u|unique:categories,name',
            'description'           =>  'required',
            'alt'                   =>  'required',
            'slug'                  =>  'required|unique:categories,slug',
            'status'                =>  'required',
            'image'                 =>  'required',
        ], [
            'name.required'                 =>  'Category Name is required',
            'name.regex'                    =>  'Please Enter Category Name in alphabets',
            'description.required'          =>  'Category Description is required',
            'alt.required'                  =>  'Category Image Alt text is required',
            'slug.required'                 =>  'Category Slug is required',
            'status.required'               =>  'Category Status is required',
            'image.required'                =>  'Category Image is required',
        ]);

        /* Storing Featured Image on local disk */
        $image = "";
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->extension();
            $file = $request->file('image');
            $fileNameString = (string) Str::uuid();
            $image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/category/', $file, $image);
        }

        /* Storing OG Image on local disk */
        $og_image = "";
        if ($request->hasFile('og_image')) {
            $extension = $request->file('og_image')->extension();
            $file = $request->file('og_image');
            $fileNameString = (string) Str::uuid();
            $og_image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/category/og_images', $file, $og_image);
        }

        /* Storing Data in Table */
        Category::create([
            'slug'                      =>  $request->slug,
            'name'                      =>  $request->name,
            'description'               =>  $request->description,
            'short_desc'                =>  $request->short_desc,
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
            'head_schema'               =>  $request->head_schema,
            'body_schema'               =>  $request->body_schema,
        ]);

        /* After Successfull insertion of data redirecting to listing page with message */
        return redirect('admin/categories')->with('success', 'Category added successfully');
    }

    public function edit($id)
    {
        if (checkpermission('CategoryController@edit')) {
            /* Getting Category data for edit using Id */
            $category = DB::table('categories')->where('id', $id)->first();
            return view('category.edit', compact('category', 'id'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function update(Request $request, $id)
    {
        /* Validating Input fields */
        $request->validate([
            'name'                  =>  'required|regex:/^[\pL\s\-]+$/u|unique:categories,name,' . $id,
            'description'           =>  'required',
            'alt'                   =>  'required',
            'slug'                  =>  'required|unique:categories,slug,' . $id,
            'status'                =>  'required',
        ], [
            'name.required'                 =>  'Category Name is required',
            'name.regex'                    =>  'Please Enter Category Name in alphabets',
            'description.required'          =>  'Category Description is required',
            'alt.required'                  =>  'Category Image Alt text is required',
            'slug.required'                 =>  'Category Slug is required',
            'status.required'               =>  'Category Status is required',
        ]);

        /* Fetching Category Data using Id*/
        $category = Category::find($id);

        /* Storing Featured Image on local disk */
        $image = $category->image;
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->extension();
            $file = $request->file('image');
            $fileNameString = (string) Str::uuid();
            $image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/category/', $file, $image);
        }

        /* Storing OG Image on local disk */
        $og_image = $category->og_image;
        if ($request->hasFile('og_image')) {
            $extension = $request->file('og_image')->extension();
            $file = $request->file('og_image');
            $fileNameString = (string) Str::uuid();
            $og_image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/category/og_images', $file, $og_image);
        }

        /* Updating Data fetched by Id */
        
            $category->slug                      =  $request->slug;
            $category->name                      =  $request->name;
            $category->description               =  $request->description;
            $category->short_desc                =  $request->short_desc;
            $category->image                     =  $image;
            $category->meta_title                =  $request->meta_title;
            $category->meta_description          =  $request->meta_description;
            $category->keywords                  =  $request->keywords;
            $category->alt                       =  $request->alt;
            $category->status                    =  $request->status;
            $category->og_title                  =  $request->og_title;
            $category->og_description            =  $request->og_description;
            $category->og_image                  =  $og_image;
            $category->head_schema               =  $request->head_schema;
            $category->body_schema               =  $request->body_schema;
            $category->update();
        

        /* After successfull update of data redirecting to index page with message */
        return redirect('admin/categories')->with('success', 'Category updated successfully');
    }

    public function update_status(Request $request)
    {
        /* Updating status of selected entry */
        $category = Category::find($request->category_id);
        $category->status   = $request->status == 1 ? 0 : 1;
        $category->update();

        if ($category->status == 1) {
            $data['msg'] = 'success';
            return response()->json($data);
        } else {
            $data['msg'] = 'danger';
            return response()->json($data);
        }
    }

    public function destroy($id)
    {
        if (checkpermission('CategoryController@destroy')) {
            /* Updating selected entry Flag to 1 for soft delete */
            $category = Category::where('id', $id)->with('subcategory.products', 'products')->first();

            $product_ids = array();
            if ($category->subcategory->isNotEmpty()) {
                foreach ($category->subcategory as $subcategory) {
                    if ($subcategory->products->isNotEmpty()) {
                        foreach ($subcategory->products as $product) {
                            array_push($product_ids, $product->id);
                        }
                    }
                }
            }

            if ($category->products->isNotEmpty()) {
                foreach ($category->products as $product) {
                    array_push($product_ids, $product->id);
                }
            }

            $carts = Cart::whereIn('product_id', $product_ids)->get();
            if ($carts->isNotEmpty()) {
                return redirect('admin/categories')->with('danger', 'Product is present in cart.');
            }

            $category->update(['flag' => 1, 'status' => 0]);
            if ($category->subcategory->isNotEmpty()) {
                foreach ($category->subcategory as $subcategory) {
                    $subcategory->update(['flag' => 1, 'status' => 0]);
                    if ($subcategory->products->isNotEmpty()) {
                        foreach ($subcategory->products as $product) {
                            $product->update(['flag' => 1, 'status' => 0]);
                        }
                    }
                }
            }

            if ($category->products->isNotEmpty()) {
                foreach ($category->products as $product) {
                    $product->update(['flag' => 1, 'status' => 0]);
                }
            }

            return redirect('admin/categories')->with('danger', 'Category deleted successfully');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    //Code for Sub Category
    public function index_sub(Request $request)
    {
        if (checkpermission('SubCategoryController@index')) {
            if (request()->ajax()) {
                /* Getting all records */
                $active = $request->active == 'all' ? array('1','2','0') : array($request->active);
                $subcategories = Category::where('parent_id', '!=', null)->whereIn('status', $active)->orderby('created_at','desc')->with('parent')->get();

                /* Converting Selected Data into desired format */
                $categories = new Collection;
                foreach ($subcategories as $subcategory) {
                    $categories->push([
                        'id'        => $subcategory->id,
                        'main'      => $subcategory->parent->name,
                        'name'      => $subcategory->name,
                        'slug'      => $subcategory->slug,
                        'status'    => $subcategory->status
                    ]);
                }

                /* Sending data through yajra datatable for server side rendering */
                return Datatables::of($categories)
                    ->addIndexColumn()
                    /* Status Active and Deactivated Checkbox */
                    ->addColumn('active', function ($row) {
                        $checked = $row['status'] == '1' ? 'checked' : '';
                        $active  = '<div class="form-check form-switch form-check-custom form-check-solid">
                                        <input type="hidden" value="' . $row['id'] . '" class="category_id">
                                        <input type="checkbox" class="form-check-input js-switch  h-20px w-30px" id="customSwitch1" name="status" value="' . $row['status'] . '" ' . $checked . '>
                                    </div>';

                        return $active;
                    })
                    /* Adding Actions like edit, delete and show */
                    ->addColumn('action', function ($row) {
                        $delete_url = url('admin/sub/categories/delete', $row['id']);
                        $edit_url = url('admin/sub/categories/edit', $row['id']);
                        $btn = '
                        <div style="display:flex;">
                        <a class="btn btn-primary btn-xs" href="' . $edit_url . '" style="margin-right: 2px;"><i class="fas fa-edit"></i></a>
                                        <form action="' . $delete_url . '" method="post">
                                            <input type="hidden" name="_token" value="' . csrf_token() . '">
                                            <input type="hidden" name="_method" value="DELETE">
                                            <button class="delete btn btn-danger btn-xs sub_category_confirm"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>';
                        return $btn;
                    })
                    ->rawColumns(['action', 'active'])
                    ->make(true);
            }
            return view('category.sub.index');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function create_sub()
    {
        if (checkpermission('SubCategoryController@create')) {
            /* Loading Create Page with Categories */
            $categories = DB::table('categories')->where('parent_id', null)->where(['flag' => 0, 'status' => 1])->get();
            return view('category.sub.create', compact('categories'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function store_sub(Request $request)
    {
        /* Validating Input fields */
        $request->validate([
            'name'                  =>  'required|regex:/^[\pL\s\-]+$/u|unique:categories,name',
            'description'           =>  'required',
            'alt'                   =>  'required',
            'slug'                  =>  'required|unique:categories,slug',
            'status'                =>  'required',
            'image'                 =>  'required',
            'parent_id'             =>  'required',
        ], [
            'name.required'                 =>  'Category Name is required',
            'name.regex'                    =>  'Please Enter Category Name in alphabets',
            'description.required'          =>  'Category Description is required',
            'alt.required'                  =>  'Category Image Alt text is required',
            'slug.required'                 =>  'Category Slug is required',
            'status.required'               =>  'Category Status is required',
            'image.required'                =>  'Category Image is required',
            'parent_id.required'            =>  'Parent Category is required',
        ]);

        /* Storing Featured Image on local disk */
        $image = "";
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->extension();
            $file = $request->file('image');
            $fileNameString = (string) Str::uuid();
            $image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/category/', $file, $image);
        }

        /* Storing OG Image on local disk */
        $og_image = "";
        if ($request->hasFile('og_image')) {
            $extension = $request->file('og_image')->extension();
            $file = $request->file('og_image');
            $fileNameString = (string) Str::uuid();
            $og_image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/category/og_images', $file, $og_image);
        }

        /* Storing Data in Table */
        Category::create([
            'slug'                      =>  $request->slug,
            'name'                      =>  $request->name,
            'parent_id'                 =>  $request->parent_id,
            'description'               =>  $request->description,
            'short_desc'                =>  $request->short_desc,
            'image'                     =>  $image,
            'meta_title'                =>  $request->meta_title,
            'meta_description'          =>  $request->meta_description,
            'keywords'                  =>  $request->keywords,
            'alt'                       =>  $request->alt,
            'status'                    =>  $request->status,
            'og_title'                  =>  $request->og_title,
            'og_description'            =>  $request->og_description,
            'og_image'                  =>  $og_image,
            'head_schema'               =>  $request->head_schema,
            'body_schema'               =>  $request->body_schema,
        ]);

        /* After Successfull insertion of data redirecting to listing page with message */
        return redirect('admin/sub/categories')->with('success', 'Sub category added successfully');
    }

    public function edit_sub($id)
    {
        if (checkpermission('SubCategoryController@edit')) {
            /* Getting Sub Category data with category for edit using Id */
            $categories = DB::table('categories')->where('parent_id', null)->where(['flag' => 0, 'status' => 1])->get();
            $category = DB::table('categories')->where('id', $id)->first();
            return view('category.sub.edit', compact('category', 'categories', 'id'));
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }

    public function update_sub(Request $request, $id)
    {
        /* Validating Input fields */
        $request->validate([
            'name'                  =>  'required|regex:/^[\pL\s\-]+$/u|unique:categories,name,' . $id,
            'description'           =>  'required',
            'alt'                   =>  'required',
            'slug'                  =>  'required|unique:categories,slug,' . $id,
            'status'                =>  'required',
            'parent_id'             =>  'required',
        ], [
            'name.required'                 =>  'Category Name is required',
            'name.regex'                    =>  'Please Enter Category Name in alphabets',
            'description.required'          =>  'Category Description is required',
            'alt.required'                  =>  'Category Image Alt text is required',
            'slug.required'                 =>  'Category Slug is required',
            'status.required'               =>  'Category Status is required',
            'parent_id.required'            =>  'Parent Category is required',
        ]);

        /* Storing Featured Image on local disk */
        $image = "";
        if ($request->hasFile('image')) {
            $extension = $request->file('image')->extension();
            $file = $request->file('image');
            $fileNameString = (string) Str::uuid();
            $image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/category/', $file, $image);
        }

        /* Storing OG Image on local disk */
        $og_image = "";
        if ($request->hasFile('og_image')) {
            $extension = $request->file('og_image')->extension();
            $file = $request->file('og_image');
            $fileNameString = (string) Str::uuid();
            $og_image = $fileNameString . time() . "." . $extension;
            Storage::putFileAs('public/category/og_images', $file, $og_image);
        }

        /* Updating Data fetched by Id */
        DB::table('categories')->where('id', $id)->update([
            'slug'                      =>  $request->slug,
            'name'                      =>  $request->name,
            'parent_id'                 =>  $request->parent_id,
            'description'               =>  $request->description,
            'short_desc'                =>  $request->short_desc,
            'image'                     =>  $image,
            'meta_title'                =>  $request->meta_title,
            'meta_description'          =>  $request->meta_description,
            'keywords'                  =>  $request->keywords,
            'alt'                       =>  $request->alt,
            'status'                    =>  $request->status,
            'og_title'                  =>  $request->og_title,
            'og_description'            =>  $request->og_description,
            'og_image'                  =>  $og_image,
            'head_schema'               =>  $request->head_schema,
            'body_schema'               =>  $request->body_schema,
        ]);

        /* After successfull update of data redirecting to index page with message */
        return redirect('admin/sub/categories')->with('success', 'Sub category updated successfully');
    }

    public function update_status_sub(Request $request)
    {
        /* Updating status of selected entry */
        $category = Category::find($request->category_id);
        $category->status   = $request->status == 1 ? 0 : 1;
        $category->update();
        if ($category->status == 1) {
            $data['msg'] = 'success';
            return response()->json($data);
        } else {
            $data['msg'] = 'danger';
            return response()->json($data);
        }
    }

    public function destroy_sub($id)
    {
        if (checkpermission('SubCategoryController@destroy')) {
            /* Updating selected entry Flag to 1 for soft delete */
            $category = Category::where('id', $id)->with('subcategory.products', 'products')->first();

            $product_ids = array();
            if ($category->subcategory->isNotEmpty()) {
                foreach ($category->subcategory as $subcategory) {
                    if ($subcategory->products->isNotEmpty()) {
                        foreach ($subcategory->products as $product) {
                            array_push($product_ids, $product->id);
                        }
                    }
                }
            }

            if ($category->products->isNotEmpty()) {
                foreach ($category->products as $product) {
                    array_push($product_ids, $product->id);
                }
            }

            $carts = Cart::whereIn('product_id', $product_ids)->get();
            if ($carts->isNotEmpty()) {
                return redirect('admin/sub/categories')->with('danger', 'Product is present in cart.');
            }

            $category->update(['flag' => 1]);
            if ($category->subcategory->isNotEmpty()) {
                foreach ($category->subcategory as $subcategory) {
                    $subcategory->update(['flag' => 1]);
                    if ($subcategory->products->isNotEmpty()) {
                        foreach ($subcategory->products as $product) {
                            $product->update(['flag' => 1, 'status' => 0]);
                        }
                    }
                }
            }

            if ($category->products->isNotEmpty()) {
                foreach ($category->products as $product) {
                    $product->update(['flag' => 1, 'status' => 0]);
                }
            }

            return redirect('admin/sub/categories')->with('danger', 'Sub Category deleted successfully');
        } else {
            return redirect()->back()->with('danger', 'You dont have required permission!');
        }
    }
}
